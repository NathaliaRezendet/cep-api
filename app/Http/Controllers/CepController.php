<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function search($ceps)
    {
        $cepArray = explode(',', $ceps);
        $result = [];

        foreach ($cepArray as $cep) {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
            if ($response->successful()) {
                $data = $response->json();
                if (!isset($data['erro'])) {
                    $result[] = [
                        'cep' => $data['cep'],
                        'logradouro' => $data['logradouro'],
                        'complemento' => $data['complemento'],
                        'bairro' => $data['bairro'],
                        'localidade' => $data['localidade'],
                        'uf' => $data['uf'],
                        'ibge' => $data['ibge'],
                        'gia' => $data['gia'],
                        'ddd' => $data['ddd'],
                        'siafi' => $data['siafi']
                    ];
                }
            }
        }

        return response()->json($result);
    }
}

