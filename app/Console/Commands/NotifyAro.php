<?php

namespace App\Console\Commands;

use App\Branch_aro;
use App\Helper\ExcelHelper;
use App\WIP;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyAro extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Weekly Notify Aro if there are vacant Ba from WIP';
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
                'data' => collect($data['wip'])->map(function ($item) {
                    return [
                        'reason' => $item['reason'],
                        'effective_date' => $item->effective_date,
                        'status' => $item->status,
                        'head_count' => $item->head_count,
                        'store_name' => $item->store->store_name_1,
                    ];
                }),
                'aro' => $data['aro']
            ];
        })->map(function ($email) {
            $this->sendMail($email);
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
