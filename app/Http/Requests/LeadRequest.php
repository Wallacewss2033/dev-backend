<?php

namespace App\Http\Requests;

use App\Services\EmailableService;
use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->merge([
            'phone' => preg_replace('/[^0-9]/', '', $this->input('phone')),
            'cpf' => preg_replace('/[^0-9]/', '', $this->input('cpf')),
        ]);

        return [
            'name' => ['required', 'string', 'max:100', function ($attribute, $value, $fail) {
                if (str_word_count($value) < 2) {
                    $fail('O campo nome deve conter pelo menos dois nomes separados por um espaço.');
                }
            }],
            'cpf' => ['required', 'string', function ($attribute, $cpf, $fail) {

                if (strlen($cpf) !== 11 ||
                    $cpf === '00000000000' ||
                    $cpf === '11111111111' ||
                    $cpf === '22222222222' ||
                    $cpf === '33333333333' ||
                    $cpf === '44444444444' ||
                    $cpf === '55555555555' ||
                    $cpf === '66666666666' ||
                    $cpf === '77777777777' ||
                    $cpf === '88888888888' ||
                    $cpf ==='99999999999'
                ) {
                    return $fail('O CPF informado é inválido.');
                }

                $sum = 0;
                for ($i = 0; $i < 9; $i++) {
                    $sum += intval($cpf[$i]) * (10 - $i);
                }

                $remainder = $sum % 11;
                $dv1 = ($remainder < 2) ? 0 : (11 - $remainder);

                if (intval($cpf[9]) != $dv1) {
                    return $fail('O CPF informado é inválido.');
                }

                $sum = 0;
                for ($i = 0; $i < 10; $i++) {
                    $sum += intval($cpf[$i]) * (11 - $i);
                }

                $remainder = $sum % 11;
                $dv2 = ($remainder < 2) ? 0 : (11 - $remainder);

                if (intval($cpf[10]) != $dv2) {
                    return $fail('O CPF informado é inválido.');
                }
            }],
            'phone' => ['required', 'string', 'size:11', function ($attribute, $phone, $fail) {

                $ddd = substr($phone, 0, 2);

                if (!preg_match('/^[1-9]{2}$/', $ddd)) {
                    $fail('O campo DDD deve ser um código de região válido no Brasil.');
                }
                if (substr($phone, 2, 1) !== '9') {
                    $fail('O primeiro dígito do telefone deve ser nove.');
                }
            
                if (substr($phone, 3, 1) == '0') {
                    $fail('O segundo dígito do telefone não deve ser zero.');
                }
            }],
            'email' => ['required', 'email', 'max:100', function($attribute, $value, $fail) {
                if(!EmailableService::verify($value)) {
                    $fail('Este email tem o score abaixo do esperado');
                }
            }],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'cpf.required' => 'O cpf é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',

            'name.max' => 'caracters excedidos.',
            'cpf.size' => 'número de caracters inválidos.',
            'email.max' => 'caracters excedidos.',
            'phone.size' => 'número de caracters inválidos.',

            'email.email' => 'e-mail inválido',
        ];
    }
}
