<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DistanceCalculatorController extends Controller
{
    public function calculateDistance(Request $request)
    {
        $origin = $this->geocodeAddress($request->input('origin'));
        $destination = $this->geocodeAddress($request->input('destination'));

        if (!$origin || !$destination) {
            return response()->json(['error' => 'Errore nella geocodifica degli indirizzi.']);
        }

        // Effettua la chiamata API a TomTom per il calcolo della route
        $client = new Client();
        $response = $client->get('https://api.tomtom.com/routing/1/calculateRoute/' . $origin . ':' . $destination . '/json', [
            'query' => [
                'key' => 'PWX9HGsOx1sGv84PlpxzgXIbaElOjVMF',
            ]
        ]);

        $data = json_decode($response->getBody());

        // Estrai la distanza dalla risposta di TomTom
        $distance = $data->routes[0]->summary->lengthInMeters;

        return response()->json(['distance' => $distance]);
    }

    // Funzione per effettuare la geocodifica di un indirizzo o una città
    private function geocodeAddress($address)
    {
        // Effettua la chiamata API a TomTom per la geocodifica
        $client = new Client();
        $response = $client->get('https://api.tomtom.com/search/2/geocode/' . $address . '.json', [
            'query' => [
                'key' => 'PWX9HGsOx1sGv84PlpxzgXIbaElOjVMF',
            ]
        ]);

        $data = json_decode($response->getBody());

        // Estrai le coordinate dalla risposta di geocodifica
        if (isset($data->results[0]->position)) {
            $coords = $data->results[0]->position;
            return $coords->lat . ',' . $coords->lon;
        }

        return null;
    }
}
