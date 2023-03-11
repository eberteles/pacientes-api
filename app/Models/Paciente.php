<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpf',
        'nome',
        'mae',
        'nascimento',
        'cns',
        'foto',
        'cep'
    ];

    public function endereco() {
        return $this->hasOne(Endereco::class);
    }
}
