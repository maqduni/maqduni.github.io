<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contributor = Role::create([
            'name' => 'Contributor',
            'slug' => 'contributor',
            'permissions' => [
                'words.list' => true,
                'words.view' => true,
                'words.create' => true,
                'words.update' => true,
                'words.delete' => true,

                'authors.list' => true,
                'authors.view' => true,
                'authors.create' => true,
                'authors.update' => true,
                'authors.delete' => true,

                'dictionaries.list' => true,
                'dictionaries.view' => true,
                'dictionaries.create' => true,
                'dictionaries.update' => true,
                'dictionaries.delete' => true,
            ]
        ]);
//        $editor = Role::create([
//            'name' => 'Editor',
//            'slug' => 'editor',
//            'permissions' => [
//                'update-post' => true,
//                'publish-post' => true,
//            ]
//        ]);
    }
}
