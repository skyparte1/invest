<?php

namespace App\Http\Requests;

use App\Support\DecimalNormalizer;
use Illuminate\Foundation\Http\FormRequest;

abstract class FinancialGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $name = $this->input('name');
        $description = $this->input('description');

        $this->merge([
            'name' => is_string($name) ? preg_replace('/\s+/u', ' ', trim($name)) : $name,
            'target_amount' => DecimalNormalizer::normalize($this->input('target_amount')),
            'current_amount' => DecimalNormalizer::normalize($this->input('current_amount')),
            'description' => is_string($description) && trim($description) !== '' ? trim($description) : null,
        ]);
    }

    protected function goalRules(array $targetDateRules): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'target_amount' => ['required', 'numeric', 'gt:0', 'max:1000000000'],
            'current_amount' => ['required', 'numeric', 'min:0', 'max:1000000000'],
            'target_date' => $targetDateRules,
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome da meta.',
            'name.string' => 'Informe um nome válido para a meta.',
            'name.max' => 'O nome da meta deve ter no máximo 100 caracteres.',
            'target_amount.required' => 'Informe o valor-alvo.',
            'target_amount.numeric' => 'Informe um valor-alvo válido.',
            'target_amount.gt' => 'O valor-alvo deve ser maior que zero.',
            'target_amount.max' => 'O valor-alvo deve ser de até R$ 1.000.000.000,00.',
            'current_amount.required' => 'Informe o valor já acumulado.',
            'current_amount.numeric' => 'Informe um valor acumulado válido.',
            'current_amount.min' => 'O valor acumulado não pode ser negativo.',
            'current_amount.max' => 'O valor acumulado deve ser de até R$ 1.000.000.000,00.',
            'target_date.required' => 'Informe a data-alvo.',
            'target_date.date' => 'Informe uma data-alvo válida.',
            'target_date.after_or_equal' => 'A data-alvo deve ser hoje ou uma data futura.',
            'description.string' => 'Informe uma descrição válida.',
            'description.max' => 'A descrição deve ter no máximo 1.000 caracteres.',
        ];
    }
}
