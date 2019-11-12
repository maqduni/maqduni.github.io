<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Policy
{
    use HandlesAuthorization;

    protected $explicitlyCheckedAbilities = [];

    public function before(User $authenticated, $ability)
    {
        if ($authenticated->isAdmin()) {
            return true;
        }

        // TODO: Consider implementing existing methods via magic methods
        $resource = $this->getResourceName();
        if (!$authenticated->hasAccess([$resource.'.'.$ability])) {
            return false;
        }

        if (!$this->isExplicitlyCheckedAbility($ability)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view a list of models.
     *
     * @param  \App\User  $authenticated
     * @return mixed
     */
    public function list(User $authenticated)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $authenticated
     * @param Model $model
     * @return mixed
     */
    public function view(User $authenticated, Model $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $authenticated
     * @return mixed
     */
    public function create(User $authenticated)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $authenticated
     * @param Model $model
     * @return mixed
     */
    public function update(User $authenticated, Model $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $authenticated
     * @param Model $model
     * @return mixed
     */
    public function delete(User $authenticated, Model $model)
    {
        //
    }

    /*
     * Helper methods
     */
    private function getResourceName()
    {
        $class = substr(strrchr(static::class, '\\'), 1, -6);
        $plural = Str::plural(strtolower($class));

        return $plural;
    }

    private function isExplicitlyCheckedAbility($ability)
    {
        return in_array($ability, $this->explicitlyCheckedAbilities);
    }
}
