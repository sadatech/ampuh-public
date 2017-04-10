<?php

namespace App\Console\Commands;

use App\Helper\ExcelHelper;
use App\Traits\ConfigTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BaConfiguration extends Command
{
    use ConfigTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'configuration:ba';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save BA configuration to history database';

    protected $excelHelper;

    /**
     * Create a new command instance.
     *
     * @param ExcelHelper $excelHelper
     */
    public function __construct(ExcelHelper $excelHelper)
    {
        parent::__construct();
        $this->excelHelper = $excelHelper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Data Konfigurasi Ba');
        $this->updateCurrentConfiguration(Carbon::now()->month, Carbon::now()->year);
        $this->travelAllStore(true);
    }

}
