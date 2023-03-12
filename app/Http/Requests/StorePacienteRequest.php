<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use phpDocumentor\Reflection\Types\Boolean;

class StorePacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $regras = [
            'nome' => [
                'required', 'min:3', 'max:100'
            ],
            'mae' => [
                'required', 'min:3', 'max:100'
            ],
            'nascimento' => [
                'required', 'date'
            ],
            'cns' => [
                'required', 'cns', 'min:15', 'max:15'
            ],
            'foto' => [
                'required', 'max:250'
            ],
            'endereco.numero' => [
                'required'
            ],
            'endereco.complemento' => [
                'required'
            ]
        ];

        if($this->method() == 'POST') {
            $regras['cpf'] = [
                'required', 'cpf', 'min:14', 'max:14', 'unique:pacientes'
            ];
        }

        return $regras;
    }
}
