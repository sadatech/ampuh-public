<?php

namespace App\Console\Commands;

use App\WIP;
use Illuminate\Console\Command;

class ClearFulfilledWipCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:wip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clearing Wip which already being fulfilled';

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
        $this->clearFulfilledWip();
    }

    /**
     * Clearing Fulfilled Wip
     *
     * @return mixed
     */
    private function clearFulfilledWip()
    {
        return WIP::where('fullfield', 'fullfield')->get()
               ->map(function ($item) {
                   $this->info('Deleting WIP ' . $item->id);
                   return WIP::find($item->id)->delete();
               });
    }
}
