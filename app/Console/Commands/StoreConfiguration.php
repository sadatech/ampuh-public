<?php

namespace App\Console\Commands;

use App\Helper\ExcelHelper;
use App\Store;
use App\StoreKonfiglog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class StoreConfiguration extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'configuration:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save Store configuration to history database';

    /**
     * Excel Helper Instance
     *
     * @var ExcelHelper
     */
    protected $excelHelper;

    /**
     * The Month for the historical configuration
     *
     * @var Int
     */
    protected $month;

    /**
     * The Year for the historical configuration
     *
     * @var Int
     */
    protected $year;


    /**
     * Collection of store configuration data
     *
     * @var Collection
     */
    protected $configurationData;

    /**
     * Given the excel filename
     *
     * @var String
     */
    protected $fileName;

    /**
     * Create a new command instance.
     *
     * @param ExcelHelper $excelHelper
     */
    public function __construct(ExcelHelper $excelHelper)
    {
        parent::__construct();

        $this->excelHelper = $excelHelper;

        $this->configurationData = Store::historyConfiguration()->get();

        $this->month = Carbon::now()->month == 1 ? 12 :Carbon::now()->subMonth()->month;

        $this->year = Carbon::now()->month == 1 ? Carbon::now()->subYear()->year : Carbon::now()->year;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Data Konfigurasi Store');

        $this->fileName = 'Data Konfigurasi Store_'. $this->month . '_' . $this->year;

        $this->saveStoreExcelHistory();

        $this->saveStoreHistory();

    }

    /**
     * Save historical excel data configuration
     *
     */
    private function saveStoreExcelHistory()
    {
        Excel::create($this->fileName, function ($excel)  {
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Konfig Toko',function ($sheet)  {
                $sheet->setAutoFilter('A1:V1');
                $sheet->setHeight(1,25);
                $sheet->fromModel($this->excelHelper->mapForStoreExcel($this->configurationData, $this->year, $this->month), null, 'A1', true, true);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#82abde');
                });
                $sheet->cells('A1:V1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:V1', 'thin');
            });
        })->store('xlsx', false, true);
    }

    /**
     * Storing historical configuration data to database
     *
     */
    private function saveStoreHistory()
    {
        $historyData = $this->excelHelper->saveStoreConfigLog($this->configurationData, $this->year, $this->month, $this->fileName .'.xlsx');

        StoreKonfiglog::insert($historyData);

    }
}
