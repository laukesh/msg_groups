<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandPlot;
use Illuminate\Http\Request;

class LandPlotController extends Controller
{
    /**
     * Display plots belonging to a land.
     */
    public function index(Land $land)
    {
        $plots = $land->plots()
            ->latest('id')
            ->paginate(15);

        return view(
            'land-acquisition.plots.index',
            compact('land', 'plots')
        );
    }


    /**
     * Show create plot form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.plots.create',
            compact('land')
        );
    }


    /**
     * Store plot.
     */
    public function store(
        Request $request,
        Land $land
    ) {
        $validated = $request->validate([

            'plot_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'survey_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'parcel_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'plot_area' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'area_unit' => [
                'nullable',
                'string',
                'max:20'
            ],

            'plot_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'boundaries' => [
                'nullable',
                'string'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['land_id'] = $land->id;
        $validated['created_by'] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        LandPlot::create($validated);


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Plot information added successfully.'
            );
    }


    /**
     * Display plot.
     */
    public function show(
        Land $land,
        LandPlot $plot
    ) {
        $this->validatePlotBelongsToLand(
            $land,
            $plot
        );

        return view(
            'land-acquisition.plots.show',
            compact('land', 'plot')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        LandPlot $plot
    ) {
        $this->validatePlotBelongsToLand(
            $land,
            $plot
        );

        return view(
            'land-acquisition.plots.edit',
            compact('land', 'plot')
        );
    }


    /**
     * Update plot.
     */
    public function update(
        Request $request,
        Land $land,
        LandPlot $plot
    ) {
        $this->validatePlotBelongsToLand(
            $land,
            $plot
        );


        $validated = $request->validate([

            'plot_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'survey_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'parcel_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'plot_area' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'area_unit' => [
                'nullable',
                'string',
                'max:20'
            ],

            'plot_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'boundaries' => [
                'nullable',
                'string'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        $validated['updated_by'] = auth()->id();


        $plot->update($validated);


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Plot information updated successfully.'
            );
    }


    /**
     * Delete plot.
     */
    public function destroy(
        Land $land,
        LandPlot $plot
    ) {
        $this->validatePlotBelongsToLand(
            $land,
            $plot
        );


        $plot->delete();


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Plot information deleted successfully.'
            );
    }


    /**
     * Ensure plot belongs to land.
     */
    private function validatePlotBelongsToLand(
        Land $land,
        LandPlot $plot
    ): void {
        abort_unless(
            $plot->land_id === $land->id,
            404
        );
    }
}
