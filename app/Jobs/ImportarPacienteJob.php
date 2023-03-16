<?php

namespace App\Jobs;

use App\Http\Controllers\CepController;
use App\Models\Paciente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;

//class ImportarPacienteJob implements ShouldQueue
class ImportarPacienteJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $nomeArquivo = '';

    /**
     * Create a new job instance.
     */
    public function __construct($nomeArquivo)
    {
        $this->nomeArquivo = $nomeArquivo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if( Storage::disk('local')->exists('importar/' . $this->nomeArquivo) ) {
            $conteudo = Storage::get( 'importar/' . $this->nomeArquivo );
            foreach( explode( "\r\n", $conteudo) as $linha ) {
                $paciente   = [];
                $dados  = explode( ";", $linha);
                if(count($dados) > 6 ) {
                    $paciente['cpf'] = $dados[0];
                    $paciente['nome'] = $dados[1];
                    $paciente['mae'] = $dados[2];
                    $paciente['nascimento'] = $dados[3];
                    $paciente['cns'] = $dados[4];
                    $paciente['foto'] = $dados[5];
                    $paciente['endereco'] = [
                        'cep' => $dados[6],
                        'numero' => $dados[7],
                        'complemento' => $dados[8],
                    ];

                    //Http::acceptJson()->get(env('APP_URL') . '/cep/' . $paciente['endereco']['cep']);
                    $pesquisaCep = Http::get('viacep.com.br/ws/' . $paciente['endereco']['cep'] . '/json/')->json();
                    $paciente['endereco']['endereco'] = $pesquisaCep['logradouro'];
                    $paciente['endereco']['bairro'] = $pesquisaCep['bairro'];
                    $paciente['endereco']['cidade'] = $pesquisaCep['localidade'];
                    $paciente['endereco']['estado'] = $pesquisaCep['uf'];

                    CadastrarPacienteJob::dispatch($paciente);
                }
            }
        }
    }
}
