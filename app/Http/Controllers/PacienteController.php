<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Resources\PacienteResource;
use App\Jobs\ImportarPacienteJob;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pacientes = Paciente::with('endereco')->paginate(4);
        return PacienteResource::collection($pacientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePacienteRequest $request)
    {
        $dados      = $request->all();

        $paciente = Paciente::create($dados);
        $paciente->endereco()->create($dados['endereco']);
        DB::commit();
        return new PacienteResource( $paciente );

    }

    public function import(Request $request)
    {
        if($request->hasFile('file') && $request->file('file')->isValid()) {
            $nomeArquivo    = uniqid(date('HisYmd')) . '.csv';
            $request->file->storeAs('importar', $nomeArquivo);

            ImportarPacienteJob::dispatch($nomeArquivo);
            return response()->json([], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paciente = Paciente::findOrFail($id)->load('endereco');

        return new PacienteResource( $paciente );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePacienteRequest $request, string $id)
    {
        $paciente   = Paciente::findOrFail($id);

        $paciente->update( $request->validated() );

        $paciente->endereco()->update( $request->all()['endereco'] );

        return new PacienteResource( $paciente );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paciente = Paciente::findOrFail($id)->delete();
        return response()->json([], 204);
    }
}
