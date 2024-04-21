<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EmailableService
{

    public static function verify(string $email)
    {

        $url = env('URL_EMAILABLE');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('TOKEN_EMAILABLE'),
            'Accept' => 'application/json',
        ])->get($url, [
            'email' => $email,
        ]);
        
        if($response->successful()) {
            
            $data = $response->json();

            return $data['score'] >= 60;

        }
    }
}
