<?php

namespace App\Console\Commands;

use App\Ba;
use App\BaSummary;
use App\Store;
use App\Traits\ConfigTrait;
use Illuminate\Console\Command;

class MigrateForConfigCommand extends Command
{
    use ConfigTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:baConfig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating summary data for the ba confguration';

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
        $this->travelAllStore();
    }

}
