<?php

namespace App\Providers;

use App\Abbreviation;
use App\Author;
use App\Comment;
use App\Definition;
use App\Dictionary;
use App\Error;
use App\Policies\AbbreviationPolicy;
use App\Policies\AuthorPolicy;
use App\Policies\CommentPolicy;
use App\Policies\DefinitionPolicy;
use App\Policies\DictionaryPolicy;
use App\Policies\ErrorPolicy;
use App\Policies\RolePolicy;
use App\Policies\SiteMapPolicy;
use App\Policies\UserPolicy;
use App\Policies\WordPolicy;
use App\Policies\WordUpdatePolicy;
use App\Role;
use App\SiteMap;
use App\User;
use App\Word;
use App\WordUpdate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
