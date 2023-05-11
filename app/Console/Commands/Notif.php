<?php

namespace App\Console\Commands;

use App\HariLibur;
use App\Present;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Notif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notif {keyword}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send notif';

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
        if ($this->argument('keyword') != null) {
            $message = $this->argument('keyword') == 'checkin' ? 'Apakah anda sudah melakukan absen masuk hari ini ?' : 'Apakah anda sudah melakukan absen pulang hari ini ?';
            $token = User::whereNotNull('token_firebase')->pluck('token_firebase')->toArray();

            $client = new Client([
                'headers' => [ 'Content-Type' => 'application/json' ,'Authorization' => 'key=AAAAvSZT2Gc:APA91bH-T0h9oJvaRkZln_ABtHrWq-mIDf7URhlmCj-vs3TPVaDaedN7IAPsQLbAsqCKld4aIGf09HLXWsMnEDEZMXiVSAidhWRLQFeyz-gqHzbuBeRi9E8YfvgYu_tY9nqKMp9AQdo2'],
            ]);
            $response = $client->post(
                'https://fcm.googleapis.com/fcm/send',
                ['body' => json_encode(
                    [
                        'registration_ids' =>$token,
                        "priority"=> "high",
                        'notification' => [
                            'title' => 'Presensi',
                            'body' => $message,
                        ],

                    ]
                )]
            );
        }
    }
}
