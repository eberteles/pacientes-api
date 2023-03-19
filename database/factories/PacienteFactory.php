<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('pt_BR');
        return [
            'cpf'           => $faker->cpf,
            'nome'          => $faker->name,
            'mae'           => $faker->name,
            'nascimento'    => $faker->date,
            'cns'           => '762667113220001',
            'foto'          => 'https://www.folhavitoria.com.br/entretenimento/blogs/du-ponto-de-vista-masculino/wp-content/uploads/2015/11/careca-800x500.jpg'
        ];
    }
}
