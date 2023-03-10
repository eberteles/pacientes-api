<?php

namespace Database\Seeders;

use App\Models\Endereco;
use App\Models\Paciente;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class PacientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            for ($i=0; $i<20; $i++){
                $paciente = Paciente::create(Paciente::factory()->make()->toArray());
                $paciente->endereco()->create(Endereco::factory()->make()->toArray());
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }
}
