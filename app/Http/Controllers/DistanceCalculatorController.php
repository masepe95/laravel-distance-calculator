<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DistanceCalculatorController extends Controller
{
    public function calculateDistance(Request $request)
    {
        $originAddress = $request->input('origin');
        $destinationAddress = $request->input('destination');

        // Effettua la geocodifica degli indirizzi di origine e destinazione
        $originCoords = $this->geocodeAddress($originAddress);
        $destinationCoords = $this->geocodeAddress($destinationAddress);

        // Verifica che la geocodifica abbia avuto successo
        if ($originCoords && $destinationCoords) {
            // Effettua la chiamata API a TomTom per il calcolo della route
            $client = new Client();
            $response = $client->get('https://api.tomtom.com/routing/1/calculateRoute/' . $originCoords . ':' . $destinationCoords . '/json', [
                'query' => [
                    'key' => 'PWX9HGsOx1sGv84PlpxzgXIbaElOjVMF',
                ]
            ]);

            $data = json_decode($response->getBody());

            // Estrai la distanza dalla risposta di TomTom
            $distance = $data->routes[0]->summary->lengthInMeters;

            return view('distance_calculator', ['distance' => $distance]);
        } else {
            // Gestisci eventuali errori di geocodifica qui
            return view('distance_calculator', ['error' => 'Errore nella geocodifica degli indirizzi']);
        }
    }

    // Funzione per effettuare la geocodifica di un indirizzo
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
