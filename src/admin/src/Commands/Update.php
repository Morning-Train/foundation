<?php

namespace morningtrain\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use morningtrain\Crud\Contracts\Controller;
use morningtrain\Crud\Contracts\Model;
use morningtrain\Janitor\Helper\MigrationHelper;
use morningtrain\Stub\Services\Stub;
use Symfony\Component\Console\Input\InputArgument;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recaches the config and updates the admin data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('config:cache');
        $this->call('migrate');

        $this->info('Everything has been successfully updated!');
    }
}
