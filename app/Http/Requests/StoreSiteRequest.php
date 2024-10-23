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
        return [
            'name' => 'required|string|max:255',
            'coordinates' => 'required|string',
            'commissioning_date' => 'required|date',
            'start_date' => 'required|date',
            'delivery_status' => 'required|in:pending,in_progress,completed',
            'financial_closure_status' => 'required|in:open,closed',
            //'sale_price' => 'nullable|numeric|min:0',
        ];
    }
}
