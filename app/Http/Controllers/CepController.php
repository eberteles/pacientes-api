<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function show($numero):JsonResponse {

        $status = 404;
        $cepCache = Cache::get($numero);
        if($cepCache == null) {
            $pesquisaCep = Http::get('viacep.com.br/ws/' . $numero . '/json/')->json();
            if(count($pesquisaCep) > 3) {
                $status = 200;
                Cache::put($numero, $pesquisaCep);
                $cepCache = $pesquisaCep;
            }
        } else $status = 200;

        return response()->json($cepCache ? $cepCache : ['erro' => 'cep não encontrado.'], $status );
    }
}
