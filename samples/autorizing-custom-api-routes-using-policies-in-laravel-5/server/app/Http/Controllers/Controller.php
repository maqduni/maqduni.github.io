<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $abilityMap = [
        'index' => 'list',
        'show' => 'view',
        'store' => 'create',
        'update' => 'update',
        'destroy' => 'delete',
    ];
    private $resourceMethodsWithoutModels = [
        'index',
        'store',
    ];

    /**
     * Register controller endpoints for authorization via 'can' middleware
     * @param $model
     * @param null $parameter
     * @param array $options
     * @return void
     */
    public function configureAuthMiddleware($model, $parameter = null, array $options = [])
    {
        // TODO: Find endpoint names dynamically, can do it via associated policy
        $parameter = $parameter ?: Str::snake(class_basename($model));

        $middleware = [];

        foreach ($this->getAbilityMap() as $method => $ability) {
            $modelName = in_array($method, $this->resourceMethodsWithoutModels()) ? $model : $parameter;

            $middleware["can:{$ability},{$modelName}"][] = $method;
        }

        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName, $options)->only($methods);
        }

//        Log::debug(json_encode(Auth::user()));
//        Log::debug(json_encode($this->getMiddleware()));
//        Log::debug(json_encode(app()->make('router')->getRoutes()->getRoutes()));
    }

    private function getAbilityMap()
    {
        return $this->abilityMap;
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        return $this->resourceMethodsWithoutModels;
    }

    /**
     * Registers resource ability.
     *
     * @param $name
     * @param bool $requiresModel
     * @param null $resourceMethodName
     * @return void
     */
    protected function registerResourceAbility($name, $requiresModel = false, $resourceMethodName = null)
    {
        $resourceMethodName = $resourceMethodName ?: $name;

        if (!isset($this->abilityMap[$name])) {
            $this->abilityMap[$name] = $resourceMethodName;
        }

        // if resource doesn't require model
        if ($requiresModel ==  false) {
            if (!in_array($resourceMethodName, $this->resourceMethodsWithoutModels)) {
                $this->resourceMethodsWithoutModels[] = $resourceMethodName;
            }
        }
    }
}
