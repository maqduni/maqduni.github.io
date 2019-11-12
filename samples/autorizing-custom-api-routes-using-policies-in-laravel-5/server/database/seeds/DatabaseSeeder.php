<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * NOTE: Run 'composer dump-autoload' to generate n ew class map.
     * https://laracasts.com/discuss/channels/lumen/unable-to-run-php-artisan-dbseed-due-to-missing-class
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

//        $this->call(DictionarySeeder::class);
//        $this->command->info('Dictionary seeds finished.');
//
//        $this->call(WordSeeder::class);
//        $this->command->info('Word seeds finished.');
    }
}
