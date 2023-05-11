<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    public function index()
    {
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ,'Authorization' => 'key=AAAAvSZT2Gc:APA91bH-T0h9oJvaRkZln_ABtHrWq-mIDf7URhlmCj-vs3TPVaDaedN7IAPsQLbAsqCKld4aIGf09HLXWsMnEDEZMXiVSAidhWRLQFeyz-gqHzbuBeRi9E8YfvgYu_tY9nqKMp9AQdo2'],
        ]);

        $response = $client->post(
            'https://fcm.googleapis.com/fcm/send',
            ['body' => json_encode(
                [
                    'to' => 'dyNt04zyo0EAntf0sutVCG:APA91bFuxiptCIu7_lcXbpliRAHLhwiW5oUkQu7TFDgiu6QzAPL2hEBRL9EHStJ_LKtcndROlsD7mK5I4gS5NgSDbHCoHQIIhwRrr1MUFoWayAm_HjwHQn2XVdczL4DRkKG_VkdkbXIQ',
                    'notification' => [
                        'title' => 'Presensi',
                        'body' => 'Atasan : Meminta Lokasi'
                    ],
                    'data' => [
                        'get' => 'request-location'
                    ]
                ]
            )]
        );
        $array = json_decode($response->getBody(), true);
        dd($array['success']);
    }
}
