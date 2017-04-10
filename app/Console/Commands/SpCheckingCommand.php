<?php

namespace App\Console\Commands;

use App\Ba;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SpCheckingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:checking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking Whether the SP is already expired or not';

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
        $haveSpBa = Ba::whereNotNull('status_sp')->where('sp_approval', 'approve')->get();
        $haveSpBa->filter(function ($item) {
            $dateInterval = Carbon::now()->diff(new \DateTime($item->tanggal_sp));
            return $dateInterval->m == 3;
        })->map(function ($item) {
            switch ($item->status_sp) {
                case 'SP1':
                    $newStatus = null;
                    break;
                case 'SP2':
                    $newStatus = 'SP1';
                    break;
                case 'SP3':
                    $newStatus = 'SP2';
                    break;
            }
            Ba::find($item->id)->update(['status_sp' => $newStatus]);
        });
    }

}
