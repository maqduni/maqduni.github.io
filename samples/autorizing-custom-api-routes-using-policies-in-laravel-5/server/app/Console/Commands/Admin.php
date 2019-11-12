<?php

namespace App\Console\Commands;

use App\AuthenticationLog;
use App\Ip;
use App\LoggedSearchRequest;
use App\PublishedSpelling;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Admin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin 
                            {function? : The name of the test function to execute}
                            {args? : Comma separated list of key-value parameter pairs or a free form parameter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test or execute arbitrary code';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $function = $this->argument('function');
        if (!method_exists($this, $function)) {
            $this->error('Method '.$function.' does not exist!');
        }

        $this->$function();
    }


    /*
     * Admin functions
     */
    public function createUser()
    {
        if (empty($this->argument('args'))) {
            $this->error('Method createUser() requires parameters email and full user name!');
        }

        $args = explode(':', $this->argument('args'));

        $email = trim($args[0]);
        if (empty($email)) {
            $this->error('Email cannot be empty!');
        }

        if (count($args) > 1) $password = $args[1];
        if (empty($password)) $password = Str::random(14);
        print_r('Password: '.$password.PHP_EOL);

        if (count($args) > 2) $name = $args[2];
        if (empty($name)) $name = Str::random(14);

        if (User::where('email', $email)->exists()) {
            $this->error('User with email '.$email.' already exists!');
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'api_token' => Str::random(60),
        ]);
    }
    public function updatePassword()
    {
        if (empty($this->argument('args'))) {
            $this->error('Method updatePassword() requires parameters email!');
        }

        $args = explode(':', $this->argument('args'));

        $email = trim($args[0]);
        if (empty($email)) {
            $this->error('Email cannot be empty!');
        }

        if (count($args) > 1) $password = $args[1];
        if (empty($password)) $password = Str::random(14);
        print_r('Password: '.$password.PHP_EOL);

        if (!User::where('email', $email)->exists()) {
            $this->error('User with email '.$email.' not found!');
        }

        User::where('email', $email)->update([
            'password' => Hash::make($password),
            'api_token' => Str::random(60),
        ]);
    }
}
