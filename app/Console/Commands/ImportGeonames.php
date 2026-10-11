<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\Pincode;
use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use ZipArchive;

/**
 * Imports Countries, States, Cities and Postal Codes from GeoNames.
 *
 * NOTE ON SCHEMA ASSUMPTIONS
 * ---------------------------------------------------------------------
 * This command writes to whatever columns exist on your `countries`,
 * `states`, `cities` and `pincodes` tables. It assumes (at minimum):
 *
 *   countries: id, geoname_id, iso2, iso3, name, phone_code, capital,
 *              currency, tld, timestamps
 *              UNIQUE(iso2)
 *
 *   states:    id, geoname_id, country_id, code, name, timestamps
 *              UNIQUE(country_id, code)
 *
 *   cities:    id, geoname_id, country_id, state_id, name, ascii_name,
 *              latitude, longitude, population, timezone, timestamps
 *              UNIQUE(geoname_id)
 *
 *   pincodes:  id, country_id, state_id, postal_code, place_name,
 *              latitude, longitude, timestamps
 *              UNIQUE(country_id, postal_code, place_name)
 *
 * Adjust the `$countryData`, `$stateData`, `$cityData` and `$postalData`
 * arrays below (and your migrations) to match your actual schema.
 * ---------------------------------------------------------------------
 */
class ImportGeonames extends Command
{
    protected $signature = 'geonames:import
        {--skip-postal : Skip importing postal codes}
        {--only-countries= : Comma separated ISO2 codes to restrict the import to, e.g. US,CA,GB}
        {--chunk=2000 : Number of rows per upsert batch}';

    protected $description = 'Import Countries, States, Cities and Postal Codes from GeoNames';

    protected string $tmpDir;

    protected int $chunkSize = 2000;

    /** @var array<string,int> ISO2 => country id */
    protected array $countryIdByIso2 = [];

    /** @var array<string,int> "ISO2.admin1code" => state id */
    protected array $stateIdByCode = [];

    /** @var string[]|null Restrict import to these ISO2 codes, null = all */
    protected ?array $onlyCountries = null;

    public function __construct()
    {
        parent::__construct();

        $this->tmpDir = storage_path('app/geonames');
    }

    public function handle(): int
    {
        DB::disableQueryLog();

        $this->chunkSize = max(100, (int) $this->option('chunk'));

        if ($only = $this->option('only-countries')) {
            $this->onlyCountries = collect(explode(',', $only))
                ->map(fn ($c) => strtoupper(trim($c)))
                ->filter()
                ->values()
                ->all();
        }

        if (! is_dir($this->tmpDir) && ! mkdir($this->tmpDir, 0755, true) && ! is_dir($this->tmpDir)) {
            $this->error("Unable to create working directory: {$this->tmpDir}");

            return self::FAILURE;
        }

        try {
            $this->info('Starting GeoNames Import...');

            $countryInfo = $this->download(
                'https://download.geonames.org/export/dump/countryInfo.txt',
                'countryInfo.txt'
            );

            $admin1 = $this->download(
                'https://download.geonames.org/export/dump/admin1CodesASCII.txt',
                'admin1CodesASCII.txt'
            );

            $citiesZip = $this->download(
                'https://download.geonames.org/export/dump/allCountries.zip',
                'allCountries.zip'
            );

            $this->unzip($citiesZip, $this->tmpDir);

            $this->info('Importing Countries...');
            $this->importCountries($countryInfo);

            $this->info('Importing States...');
            $this->importStates($admin1);

            $this->info('Importing Cities...');
            $this->importCities($this->tmpDir.'/allCountries.txt');

            if (! $this->option('skip-postal')) {
                $postalZip = $this->download(
                    'https://download.geonames.org/export/zip/allCountries.zip',
                    'postal_allCountries.zip'
                );

                $this->unzip($postalZip, $this->tmpDir);

                $this->info('Importing Postal Codes...');
                $this->importPostal($this->tmpDir.'/allCountries.txt');
            }

            $this->info('GeoNames Import Completed Successfully.');

            return self::SUCCESS;
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Download / Extract helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Download a file, streaming to disk (safe for large files), with a
     * progress bar. Returns the local path.
     */
    protected function download(string $url, string $filename): string
    {
        $path = $this->tmpDir.DIRECTORY_SEPARATOR.$filename;

        if (file_exists($path) && filesize($path) > 0) {
            $this->line("Using cached file: {$filename}");

            return $path;
        }

        $this->line("Downloading {$filename}...");

        $tmpPath = $path.'.part';
        $fh = fopen($tmpPath, 'w');

        if ($fh === false) {
            throw new RuntimeException("Unable to open {$tmpPath} for writing.");
        }

        $progressBar = null;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FILE => $fh,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_NOPROGRESS => false,
            CURLOPT_PROGRESSFUNCTION => function ($resource, $downloadSize, $downloaded) use (&$progressBar) {
                if ($downloadSize <= 0) {
                    return;
                }

                if ($progressBar === null) {
                    $progressBar = $this->output->createProgressBar($downloadSize);
                    $progressBar->setFormat(' %current%/%max% bytes [%bar%] %percent:3s%%');
                    $progressBar->start();
                }

                $progressBar->setProgress((int) $downloaded);
            },
        ]);

        $success = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fh);

        if ($progressBar !== null) {
            $progressBar->finish();
            $this->newLine();
        }

        if ($success === false || $httpCode >= 400) {
            @unlink($tmpPath);

            throw new RuntimeException("Unable to download {$url}" . ($error ? " ({$error})" : " (HTTP {$httpCode})"));
        }

        rename($tmpPath, $path);

        return $path;
    }

    /**
     * Extract a ZIP archive.
     */
    protected function unzip(string $zipFile, string $destination): void
    {
        $zip = new ZipArchive();

        if ($zip->open($zipFile) !== true) {
            throw new RuntimeException("Unable to extract {$zipFile}");
        }

        $zip->extractTo($destination);
        $zip->close();
    }

    /**
     * Open a file for line-by-line reading. Throws if it can't be opened.
     *
     * @return resource
     */
    protected function openFile(string $path)
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException("Unable to open {$path}");
        }

        return $handle;
    }

    /*
    |--------------------------------------------------------------------------
    | Import Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Import countries from countryInfo.txt (tab separated, '#' = comment).
     *
     * Columns: ISO, ISO3, ISO-Numeric, fips, Country, Capital, Area,
     * Population, Continent, tld, CurrencyCode, CurrencyName, Phone,
     * PostalCodeFormat, PostalCodeRegex, Languages, geonameid,
     * neighbours, EquivalentFipsCode
     */
    protected function importCountries(string $file): void
    {
        $handle = $this->openFile($file);
        $now = now();
        $batch = [];
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");

            if ($line === '' || $line[0] === '#') {
                continue;
            }

            $cols = explode("\t", $line);

            if (count($cols) < 17) {
                continue;
            }

            $iso2 = strtoupper($cols[0]);

            if ($this->onlyCountries && ! in_array($iso2, $this->onlyCountries, true)) {
                continue;
            }

           $batch[] = [
                'geoname_id' => !empty($cols[16]) ? (int) $cols[16] : null,
                'iso2'        => $cols[0],
                'iso3'        => $cols[1] ?: null,
                'name'        => $cols[4],
                'capital'     => $cols[5] ?: null,
                'phone_code'  => $cols[12] ?: null,
                'currency'    => $cols[10] ?: null,
                'tld'         => !empty($cols[9]) ? ltrim($cols[9], '.') : null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            $count++;

            if (count($batch) >= $this->chunkSize) {
                $this->upsertCountries($batch);
                $batch = [];
            }
        }

        if ($batch) {
            $this->upsertCountries($batch);
        }

        fclose($handle);

        // Build the ISO2 => id lookup used by states/cities/postal codes.
        $this->countryIdByIso2 = Country::query()->pluck('id', 'iso2')->all();

        $this->line("  {$count} countries processed.");
    }

    protected function upsertCountries(array $rows): void
    {
        Country::query()->upsert(
            $rows,
            ['iso2'],
            ['geoname_id', 'iso3', 'name', 'capital', 'phone_code', 'currency', 'tld', 'updated_at']
        );
    }

    /**
     * Import states/regions from admin1CodesASCII.txt (tab separated).
     *
     * Columns: code (e.g. "US.CA"), name, name ascii, geonameid
     */
    protected function importStates(string $file): void
    {
        $handle = $this->openFile($file);
        $now = now();
        $batch = [];
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");

            if ($line === '') {
                continue;
            }

            $cols = explode("\t", $line);

            if (count($cols) < 4) {
                continue;
            }

            [$iso2, $adminCode] = array_pad(explode('.', $cols[0], 2), 2, null);
            $iso2 = strtoupper((string) $iso2);

            if ($this->onlyCountries && ! in_array($iso2, $this->onlyCountries, true)) {
                continue;
            }

            $countryId = $this->countryIdByIso2[$iso2] ?? null;

            if ($countryId === null || $adminCode === null) {
                continue;
            }

            $batch[] = [
                'geoname_id' => (int) $cols[3],
                'country_id' => $countryId,
                'code' => $adminCode,
                'name' => $cols[1],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $count++;

            if (count($batch) >= $this->chunkSize) {
                $this->upsertStates($batch);
                $batch = [];
            }
        }

        if ($batch) {
            $this->upsertStates($batch);
        }

        fclose($handle);

        // Build "ISO2.admincode" => state id lookup for cities/postal codes.
        $this->stateIdByCode = [];

      State::query()
            ->join('countries', 'countries.id', '=', 'states.country_id')
            ->select(
                'states.id as state_id',
                'states.code',
                'countries.iso2'
            )
            ->orderBy('states.id')
            ->chunkById(5000, function ($rows) {
                foreach ($rows as $row) {
                    $this->stateIdByCode["{$row->iso2}.{$row->code}"] = $row->state_id;
                }
            }, 'states.id', 'state_id');

        $this->line("  {$count} states processed.");
    }

    protected function upsertStates(array $rows): void
    {
        State::query()->upsert(
            $rows,
            ['country_id', 'code'],
            ['geoname_id', 'name', 'updated_at']
        );
    }

    /**
     * Import cities from allCountries.txt (tab separated).
     *
     * Only rows with feature class 'P' (populated place) are imported,
     * otherwise this dataset balloons to ~12M rows of every feature type.
     *
     * Columns: geonameid, name, asciiname, alternatenames, latitude,
     * longitude, feature class, feature code, country code, cc2,
     * admin1 code, admin2 code, admin3 code, admin4 code, population,
     * elevation, dem, timezone, modification date
     */
    protected function importCities(string $file): void
    {
        $handle = $this->openFile($file);
        $now = now();
        $batch = [];
        $count = 0;
        $skipped = 0;

        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");

            if ($line === '') {
                continue;
            }

            $cols = explode("\t", $line);

            if (count($cols) < 19 || $cols[6] !== 'P') {
                continue;
            }

            $iso2 = strtoupper($cols[8]);

            if ($this->onlyCountries && ! in_array($iso2, $this->onlyCountries, true)) {
                continue;
            }

            $countryId = $this->countryIdByIso2[$iso2] ?? null;

            if ($countryId === null) {
                $skipped++;
                continue;
            }

            $stateId = $this->stateIdByCode["{$iso2}.{$cols[10]}"] ?? null;

          
               $batch[] = [
                'geoname_id' => (int) $cols[0],
                'country_id' => $countryId,
                'state_id' => $stateId,
                'name' => $cols[1],
                'ascii_name' => $cols[2],
                'latitude' => $cols[4] !== '' ? (float) $cols[4] : null,
                'longitude' => $cols[5] !== '' ? (float) $cols[5] : null,
                'population' => $cols[14] !== '' ? (int) $cols[14] : null,
                'timezone' => $cols[17] ?: null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $count++;

            if (count($batch) >= $this->chunkSize) {
                $this->upsertCities($batch);
                $batch = [];

                if ($count % ($this->chunkSize * 20) === 0) {
                    $this->line("  {$count} cities imported so far...");
                }
            }
        }

        if ($batch) {
            $this->upsertCities($batch);
        }

        fclose($handle);

        $this->line("  {$count} cities processed, {$skipped} skipped (unknown country).");
    }

    protected function upsertCities(array $rows): void
    {
        City::query()->upsert(
            $rows,
            ['geoname_id'],
            ['country_id', 'state_id', 'name', 'ascii_name', 'latitude', 'longitude', 'population', 'timezone', 'updated_at']
        );
    }

    /**
     * Import postal codes from the GeoNames postal zip allCountries.txt
     * (tab separated).
     *
     * Columns: country code, postal code, place name, admin name1,
     * admin code1, admin name2, admin code2, admin name3, admin code3,
     * latitude, longitude, accuracy
     *
     * City linking is intentionally skipped: matching postal rows to a
     * specific city by name alone is unreliable at this scale. state_id
     * is resolved via admin code1.
     */
    protected function importPostal(string $file): void
    {
        $handle = $this->openFile($file);
        $now = now();
        $batch = [];
        $count = 0;
        $skipped = 0;

        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");

            if ($line === '') {
                continue;
            }

            $cols = explode("\t", $line);

            if (count($cols) < 11) {
                continue;
            }

            $iso2 = strtoupper($cols[0]);

            if ($this->onlyCountries && ! in_array($iso2, $this->onlyCountries, true)) {
                continue;
            }

            $countryId = $this->countryIdByIso2[$iso2] ?? null;

            if ($countryId === null) {
                $skipped++;
                continue;
            }

            $stateId = $this->stateIdByCode["{$iso2}.{$cols[4]}"] ?? null;

            $batch[] = [
                'country_id' => $countryId,
                'state_id' => $stateId,
                'postal_code' => $cols[1],
                'place_name' => $cols[2],
                'latitude' => $cols[9] !== '' ? (float) $cols[9] : null,
                'longitude' => $cols[10] !== '' ? (float) $cols[10] : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $count++;

            if (count($batch) >= $this->chunkSize) {
                $this->upsertPostal($batch);
                $batch = [];

                if ($count % ($this->chunkSize * 20) === 0) {
                    $this->line("  {$count} postal codes imported so far...");
                }
            }
        }

        if ($batch) {
            $this->upsertPostal($batch);
        }

        fclose($handle);

        $this->line("  {$count} postal codes processed, {$skipped} skipped (unknown country).");
    }

    protected function upsertPostal(array $rows): void
    {
        Pincode::query()->upsert(
            $rows,
            ['country_id', 'postal_code', 'place_name'],
            ['state_id', 'latitude', 'longitude', 'updated_at']
        );
    }
}