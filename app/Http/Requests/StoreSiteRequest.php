<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'coordinates' => 'required|string',
            'commissioning_date' => 'required|date',
            'start_date' => 'required|date',
            'delivery_status' => 'required|in:pending,in_progress,completed',
            'financial_closure_status' => 'required|in:open,closed',
            'capital' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'profit_or_loss_ratio' => 'nullable|numeric',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // Change 'required' rules to 'sometimes' for update
            foreach ($rules as $field => $rule) {
                $rules[$field] = str_replace('required', 'sometimes', $rule);
            }
        }

        return $rules;
    }
}
