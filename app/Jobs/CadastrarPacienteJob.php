<?php

namespace App\Jobs;

use App\Models\Paciente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

//class CadastrarPacienteJob implements ShouldQueue
class CadastrarPacienteJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $paciente;

    /**
     * Create a new job instance.
     */
    public function __construct(array $paciente)
    {
        $this->paciente = $paciente;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();
            $db = Paciente::create($this->paciente);
            $db->endereco()->create($this->paciente['endereco']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
