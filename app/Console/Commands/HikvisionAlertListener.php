<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HikvisionAlertListener extends Command
{
    protected $signature = 'hikvision:listen-alerts';
    protected $description = 'Listen to Hikvision alertStream';

    public function handle()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('HIKVISION_BASE_URL'),
            'auth'     => ['admin', '1122@Abc', 'digest'],
            'timeout'  => 0,
            'read_timeout' => 0,
        ]);

        $response = $client->request('GET', '/ISAPI/Event/notification/alertStream', [
            'headers' => [
                'Connection' => 'keep-alive',
                'Accept' => 'multipart/x-mixed-replace',
            ],
            'stream' => true,
        ]);

        $body = $response->getBody();

        while (!$body->eof()) {
            $chunk = $body->read(1024);

            if (trim($chunk)) {
                logger($chunk);
                // parse + save to DB
            }
        }
    }
}

