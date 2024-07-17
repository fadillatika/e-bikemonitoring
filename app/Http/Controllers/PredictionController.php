<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function getPrediction(Request $request)
    {
        $percentage = $request->input('percentage');
        $voltage = $request->input('voltage');
        $time = $request->input('time');

        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/predict', [
            'json' => [
                'percentage' => $percentage,
                'voltage' => $voltage,
                'time' => $time
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        return response()->json($result);
    }
}
