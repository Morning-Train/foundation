<?php

namespace morningtrain\Acl\Commands;

use Illuminate\Console\Command;
use morningtrain\Acl\Extensions\Roleable;
use morningtrain\Acl\Models\Permission;
use morningtrain\Acl\Models\Role;
use Symfony\Component\Console\Input\InputArgument;

class Seed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:seed {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds the database with a user for each role';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->hash = app()->make('hash');
    }

    /*
     * Libraries
     */

    protected $hash;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = config('auth.providers.users.model', '\\App\\User');

        // Validate model
        if (!class_exists($model)) {
            $this->error('The user model `' . $model . '` does not exist!');
        }

        // Prepare data
        $roles = config('acl.roles', []);
        $domain = $this->option('domain');

        if (!isset($domain) || (strlen($domain) === 0)) {
            $domain = 'morningtrain.dk';
        }

        foreach ($roles as $slug => $data) {
            // Find role
            $role = Role::where('slug', $slug)->first();

            if (is_null($role)) {
                continue;
            }

            // Check if a user with this role already exists
            $user = $model::whereHas('roles', function ($query) use ($role) {
                $query->where('role_id', $role->id);

            })->first();

            if (!is_null($user)) {
                continue;
            }

            // Create the user
            $user = new $model();
            $user->name = $role->display_name;
            $user->email = "$slug@$domain";
            $user->password = $this->hash->make($slug);
            $user->save();

            // Attach the role
            $user->roles()->attach($role->id);
        }

        $this->info('The users have been seeded!');
    }
}
