<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\CadastrarPacienteJob;
use App\Jobs\ImportarPacienteJob;
use App\Models\Endereco;
use App\Models\Paciente;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;
use Tests\TestCase;
use function Psy\debug;

class PacienteTest extends TestCase
{
    /**
     * @test
     */
    public function usuario_quer_listar_todos_pacientes(): void
    {
        try {
            $response = $this->json('get', '/pacientes');

            $response->assertStatus(200);
        }catch (Exception $ex) {
            dd($ex);
        }

    }

    /**
     * @test
     */
    public function usuario_quer_recuperar_um_paciente(): void
    {
        $paciente = Paciente::all()->first();

        try {
            $response = $this->json('get', '/pacientes/' . $paciente['id']);

            $response->assertStatus(200);
        }catch (Exception $ex) {
            dd($ex);
        }

    }

    /**
     * @test
     */
    public function usuario_quer_consultar_um_cep(): void
    {
        try {
            $response = $this->json('get', '/cep/01001000');

            $response->assertStatus(200);
        }catch (Exception $ex) {
            dd($ex);
        }

    }

    /**
     * @test
     */
    public function usuario_consulta_um_cep_invalido(): void
    {
        try {
            $response = $this->json('get', '/cep/01001009');

            $response->assertStatus(404);
        }catch (Exception $ex) {
            dd($ex);
        }

    }

    /**
     * @test
     */
    public function usuario_quer_cadastrar_um_novo_paciente(): void
    {
        try {
            $paciente   = Paciente::factory()->make()->toArray();
            $paciente['endereco']   = Endereco::factory()->make()->toArray();

            $response = $this->json('post', '/pacientes', $paciente);

            $response->assertStatus(201);
        }catch (Exception $ex) {
            dd($ex);
        }
    }

    /**
     * @test
     */
    public function usuario_quer_cadastrar_um_novo_paciente_sem_informar_endereco(): void
    {

        $paciente   = Paciente::factory()->make()->toArray();

        $response = $this->json('post', '/pacientes', $paciente);

        $response->assertStatus(422);

    }

    /**
     * @test
     */
    public function usuario_quer_alterar_um_paciente(): void
    {
        $pacienteAlt = Paciente::all()->first();
        try {
            $paciente   = Paciente::factory()->make()->toArray();
            $paciente['endereco']   = Endereco::factory()->make()->toArray();

            $response = $this->json('patch', '/pacientes/' . $pacienteAlt['id'], $paciente);

            $response->assertStatus(200);
        }catch (Exception $ex) {
            dd($ex);
        }
    }

    /**
     * @test
     */
    public function usuario_quer_excluir_um_paciente(): void
    {
        $paciente = Paciente::all()->first();
        try {
            $response = $this->json('delete', '/pacientes/' . $paciente['id']);

            $response->assertStatus(204);
        }catch (Exception $ex) {
            dd($ex);
        }
    }

    /**
     * @test
     */
    public function usuario_quer_importar_pacientes(): void
    {
        Storage::fake('importar');

        $paciente   = Paciente::factory()->make()->toArray();
        $endereco   = Endereco::factory()->make()->toArray();

        $conteudo = $paciente['cpf'] . ';' . $paciente['nome'] . ';' . $paciente['mae']
            . ';' . $paciente['nascimento'] . ';' . $paciente['cns'] . ';' . $paciente['foto']
            . ';72300543;' . $endereco['numero'] . ';' . $endereco['complemento'];

        $file = UploadedFile::fake()->createWithContent(
            'teste.csv',
            $conteudo
        );

        $response = $this->json('post', '/import', [
            'file' => $file
        ]);

        $response->assertStatus(200);

    }

}
