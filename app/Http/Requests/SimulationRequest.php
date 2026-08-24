<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SimulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'initial_amount' => $this->normalizeDecimal($this->input('initial_amount')),
            'periodic_contribution' => $this->normalizeDecimal($this->input('periodic_contribution')),
            'annual_rate' => $this->normalizeDecimal($this->input('annual_rate')),
        ]);
    }

    public function rules(): array
    {
        return [
            'initial_amount' => ['required', 'numeric', 'min:0', 'max:1000000000'],
            'periodic_contribution' => ['required', 'numeric', 'min:0', 'max:1000000000'],
            'annual_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'term' => ['required', 'integer', 'min:1'],
            'term_unit' => ['required', Rule::in(['months', 'years'])],
            'contribution_frequency' => ['required', Rule::in(['monthly'])],
            'investment_slug' => [
                'bail',
                'nullable',
                'string',
                Rule::exists('investments', 'slug')->where(
                    fn (Builder $query) => $query->where('is_published', true)
                ),
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $term = filter_var($this->input('term'), FILTER_VALIDATE_INT);
                $unit = $this->input('term_unit');

                if ($term !== false && (($unit === 'years' && $term > 100) || ($unit === 'months' && $term > 1200))) {
                    $validator->errors()->add('term', 'O prazo máximo para esta simulação é de 100 anos ou 1.200 meses.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'initial_amount.required' => 'Informe o valor inicial.',
            'initial_amount.numeric' => 'Informe um valor inicial válido.',
            'initial_amount.min' => 'O valor inicial não pode ser negativo.',
            'initial_amount.max' => 'O valor inicial deve ser de até R$ 1.000.000.000,00.',
            'periodic_contribution.required' => 'Informe o aporte periódico.',
            'periodic_contribution.numeric' => 'Informe um aporte periódico válido.',
            'periodic_contribution.min' => 'O aporte periódico não pode ser negativo.',
            'periodic_contribution.max' => 'O aporte periódico deve ser de até R$ 1.000.000.000,00.',
            'annual_rate.required' => 'Informe a taxa anual hipotética.',
            'annual_rate.numeric' => 'Informe uma taxa anual válida.',
            'annual_rate.min' => 'A taxa anual deve estar entre 0% e 100%.',
            'annual_rate.max' => 'A taxa anual deve estar entre 0% e 100%.',
            'term.required' => 'Informe o prazo.',
            'term.integer' => 'O prazo deve ser um número inteiro.',
            'term.min' => 'O prazo deve ser de pelo menos 1 mês ou 1 ano.',
            'term_unit.required' => 'Selecione a unidade do prazo.',
            'term_unit.in' => 'Selecione Meses ou Anos como unidade do prazo.',
            'contribution_frequency.required' => 'Selecione a frequência do aporte.',
            'contribution_frequency.in' => 'Nesta versão, a frequência disponível é mensal.',
            'investment_slug.exists' => 'Selecione uma modalidade publicada válida.',
        ];
    }

    private function normalizeDecimal(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $normalized = str_replace(['R$', ' ', "\u{00A0}"], '', trim($value));

        if (! preg_match('/^-?[0-9.,]+$/', $normalized)) {
            return $value;
        }

        $lastComma = strrpos($normalized, ',');
        $lastDot = strrpos($normalized, '.');

        if ($lastComma === false && $lastDot === false) {
            return $normalized;
        }

        $decimalPosition = max($lastComma === false ? -1 : $lastComma, $lastDot === false ? -1 : $lastDot);
        $integer = preg_replace('/[.,]/', '', substr($normalized, 0, $decimalPosition));
        $fraction = preg_replace('/[.,]/', '', substr($normalized, $decimalPosition + 1));

        return $fraction === '' ? $integer : $integer.'.'.$fraction;
    }
}
