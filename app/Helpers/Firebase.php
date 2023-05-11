<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class Firebase
{
    public static function getLocation($devicesTokens = [])
    {
        $dt['success'] = 0;
        $dt['user'] = [];
        foreach ($devicesTokens as $key => $token) {
            $client = new Client([
                'headers' => [ 'Content-Type' => 'application/json' ,'Authorization' => 'key=AAAAvSZT2Gc:APA91bH-T0h9oJvaRkZln_ABtHrWq-mIDf7URhlmCj-vs3TPVaDaedN7IAPsQLbAsqCKld4aIGf09HLXWsMnEDEZMXiVSAidhWRLQFeyz-gqHzbuBeRi9E8YfvgYu_tY9nqKMp9AQdo2'],
            ]);

            $response = $client->post(
                'https://fcm.googleapis.com/fcm/send',
                ['body' => json_encode(
                    [
                        'to' => $token,
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
            if ($array['success'] == 1) {
                $dt['success'] = $dt['success'] + 1;
                $dt['user'][] = $key;
            }
        }


        return $dt;
    }
}
