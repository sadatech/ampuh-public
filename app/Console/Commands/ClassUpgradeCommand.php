<?php

namespace App\Console\Commands;

use App\Ba;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClassUpgradeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'class:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrading Class for those who already work for more than 2 years every june';

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
        $currentMonth = Carbon::now()->month;
        if ($currentMonth == 7) {
            $allBa = Ba::whereIn('class', [1, 2, 3])->get();
            $allBa->map(function ($item) {
                $workingTime = Carbon::now()->diff(new \DateTime($item->join_date));
                if ($workingTime->y >= 2) {
                    $upgradeClass = (int) $item->class + 1;
                    Ba::find($item->id)->update(['class' => $upgradeClass]);
                }
            });
        }
    }
}
