<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Resources\PacienteResource;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pacientes = Paciente::with('endereco')->get();
        return PacienteResource::collection($pacientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePacienteRequest $request)
    {
        $dados      = $request->all();

        try {
            DB::beginTransaction();
            $paciente = Paciente::create($dados);
            $paciente->endereco()->create($dados['endereco']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return new PacienteResource( $paciente );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new PacienteResource( Paciente::findOrFail($id) );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePacienteRequest $request, string $id)
    {
        $paciente   = Paciente::findOrFail($id);

        try {
            DB::beginTransaction();
            $paciente->update( $request->validated() );

            $paciente->endereco()->update( $request->all()['endereco'] );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

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
