<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmsCountService;

class sendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-sms {mobile} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(SmsCountService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mobile = $this->argument('mobile');
        $message = $this->argument('message');

        // if ($this->runningInConsole())
        // {
        //     dump("testing this command: Mobile->$mobile Msg->$message");
        // }

        $url = 'https://japi.instaalerts.zone/httpapi/QueryStringReceiver?ver=1.0&key=yyBY673SB67Tz4R4joGgRA==&dest=' . $mobile . '&send=GOADIP&text=' . rawurlencode($message);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // dump($response);

        if(!strpos($response,'&'))
        {
            $this->error('Failed to send SMS.');
        }
        else
        {
            $res = explode('&', $response)[1];
            // dump($res);

            if (explode('=', $res)[1] == 200) {
                $this->info('SMS sent successfully!');
                $this->smsService->increment();
            } else {
                $this->error('Failed to send SMS.');
            }
        }

    }
}
