<?php

namespace App\Console\Commands;

use App\Branch_aro;
use App\Helper\ExcelHelper;
use App\User;
use App\WIP;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyAroDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Aro daily if there are vacant Ba from WIP in 7 days interval';
    /**
     * @var ExcelHelper
     */
    private $excelHelper;

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
        $allAro = Branch_aro::with('user')->groupBy('user_id')->get();

        return $allAro->map(function ($item) {
            return ['wip' => WIP::NotifyAro($item['user_id'])->get(), 'aro' => $item];
        })->map(function ($data) {
            return [
                'data' => collect($data['wip'])
                    ->filter(function ($item) {
                        $dateInterval = Carbon::now()->diff(new \DateTime($item->effective_date));
                        return $dateInterval->y == 0 && $dateInterval->m == 0 && $dateInterval->d <= 7;
                    })
                    ->map(function ($item) {
                        return [
                            'reason' => $item['reason'],
                            'effective_date' => $item->effective_date->format('d-M-y'),
                            'status' => $item->status,
                            'head_count' => $item->head_count,
                            'store_name' => $item->store->store_name_1,
                        ];
                    }),
                'aro' => $data['aro']
            ];
        })->filter(function($item) {
            return count($item['data']) != 0;
        })->map(function ($email) {
            dd($email);
            return $this->sendMail($email);
        });
    }

    /**
     * Sending Email to the Aro
     *
     * @param $emailData
     * @return
     */
    private function sendMail($emailData)
    {
        return Mail::to($emailData['aro']['user']['email'])->send(new \App\Mail\NotifyAro($emailData['data']));

    }
}
