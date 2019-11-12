<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
        'timezone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    /*
     * Relationships
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /*
     * Transformers
     */
    public function getAbridgedAttribute()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'timezone' => $this->timezone,
            'is_admin' => $this->is_admin,
            'roles' => $this->roles->mapWithKeys(function($item) {
                return [$item->slug => $item->permissions];
            }),
            'app_settings' => $this->app_settings,
        ];
    }

    public function getAppSettingsAttribute()
    {
        return [
            'approval_flow_enabled' => config('app.approval_flow_enabled'),
        ];
    }

    /*
     * Functional methods
     */
    /**
     * Checks if User has access to $permissions.
     * @param array $permissions
     * @return bool
     */
    public function hasAccess(array $permissions) : bool
    {
        // check if the permission is available in any role
        foreach ($this->roles as $role) {
            if($role->hasAccess($permissions)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if the user belongs to role.
     * @param string $roleSlug
     * @return bool
     */
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->count() == 1;
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }
}
