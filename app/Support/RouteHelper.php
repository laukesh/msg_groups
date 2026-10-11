<?php

namespace App\Support;

use Closure;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Small helpers that keep the module route files short.
 * Route names / URIs / parameter names are identical to the old monolithic web.php.
 */
class RouteHelper
{
    public const NO_SHOW = ['index', 'create', 'store', 'edit', 'update', 'destroy'];
    public const DOCS    = ['index', 'create', 'store', 'show', 'destroy'];

    /** ['claim' => '[0-9]+', ...] – equivalent of ->whereNumber('claim') */
    public static function num(string ...$params): array
    {
        return array_fill_keys($params, '[0-9]+');
    }

    /** Apply *.view / *.create / *.edit / *.delete permissions to a resource. */
    public static function permissions(PendingResourceRegistration $r, string $perm): PendingResourceRegistration
    {
        return $r->middlewareFor(['index', 'show'], "permission:$perm.view")
            ->middlewareFor(['create', 'store'], "permission:$perm.create")
            ->middlewareFor(['edit', 'update'], "permission:$perm.edit")
            ->middlewareFor('destroy', "permission:$perm.delete");
    }

    /**
     * POST workflow routes: /{param}/{segment}  ->  name = segment.
     * 'submit'                    => method submit()
     * 'start-review'              => method startReview()
     * 'revision' => 'createRevision' (explicit method)
     */
    public static function actions(string $controller, string $param, array $actions): void
    {
        foreach ($actions as $segment => $method) {
            if (is_int($segment)) {
                $segment = $method;
                $method  = Str::camel($method);
            }
            Route::post('/{' . $param . '}/' . $segment, [$controller, $method])->name($segment);
        }
    }

    /**
     * Prefixed + named group containing: $before routes, POST actions, then CRUD.
     *
     * @param string[] $only          limit CRUD actions (empty = all 7)
     * @param array    $actions       see actions()
     * @param array    $where         route parameter constraints
     * @param string   $methodPrefix  e.g. 'global' => globalIndex, globalCreate ...
     */
    public static function crud(
        string $uri,
        string $name,
        string $controller,
        string $param,
        array $only = [],
        array $actions = [],
        array $where = [],
        ?Closure $before = null,
        string $methodPrefix = ''
    ): void {
        $registrar = Route::prefix($uri)->name($name);
        if ($where) {
            $registrar = $registrar->where($where);
        }

        $registrar->group(function () use ($controller, $param, $only, $actions, $before, $methodPrefix) {
            if ($before) {
                $before();
            }
            self::actions($controller, $param, $actions);

            $p    = '{' . $param . '}';
            $defs = [
                'index'   => ['get', '/'],
                'create'  => ['get', '/create'],
                'store'   => ['post', '/'],
                'show'    => ['get', "/$p"],
                'edit'    => ['get', "/$p/edit"],
                'update'  => ['put', "/$p"],
                'destroy' => ['delete', "/$p"],
            ];

            foreach ($defs as $action => [$verb, $path]) {
                if ($only && !in_array($action, $only, true)) {
                    continue;
                }
                $method = $methodPrefix ? $methodPrefix . ucfirst($action) : $action;
                Route::$verb($path, [$controller, $method])->name($action);
            }
        });
    }
}
