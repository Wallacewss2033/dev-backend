<?php

namespace App\Http\Requests;

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
        return [
            'name' => ['required', 'string', 'max:100', function ($attribute, $value, $fail) {
                if (str_word_count($value) < 2) {
                    $fail('O campo nome deve conter pelo menos dois nomes separados por um espaço.');
                }
            }],
            'cpf' => ['required', 'string', 'max:14', 'unique:leads,cpf', function ($attribute, $value, $fail) {
                $cpf = preg_replace('/[^0-9]/', '', $value);
                if (strlen($cpf) != 11 ||
                    $cpf == '00000000000' ||
                    $cpf == '11111111111' ||
                    $cpf == '22222222222' ||
                    $cpf == '33333333333' ||
                    $cpf == '44444444444' ||
                    $cpf == '55555555555' ||
                    $cpf == '66666666666' ||
                    $cpf == '77777777777' ||
                    $cpf == '88888888888' ||
                    $cpf == '99999999999'
                ) {
                    $fail('O CPF informado é inválido.');
                }

                $sum = 0;
                for ($i = 0; $i < 9; $i++) {
                    $sum += intval($cpf[$i]) * (10 - $i);
                }

                $remainder = $sum % 11;
                $dv1 = ($remainder < 2) ? 0 : (11 - $remainder);

                if (intval($cpf[9]) != $dv1) {
                    $fail('O CPF informado é inválido.');
                }

                $sum = 0;
                for ($i = 0; $i < 10; $i++) {
                    $sum += intval($cpf[$i]) * (11 - $i);
                }

                $remainder = $sum % 11;
                $dv2 = ($remainder < 2) ? 0 : (11 - $remainder);

                if (intval($cpf[10]) != $dv2) {
                    $fail('O CPF informado é inválido.');
                }
            }],
            'phone' => ['nullable', 'string', 'size:11', 'regex:/^([1-9]{2})(9[1-9])[0-9]{7}$/'],
            'email' => ['required', 'email', 'unique:leads,email', 'max:100'],
        ];
    }
}
