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
        if ($this->isUpdateRequest()) {
            // For updates, validate fields only if they are present
            return [
                'name' => 'sometimes|string|max:255',
                'code' => 'sometimes|string|max:50',
                'coordinates' => 'sometimes|string',
                'commissioning_date' => 'sometimes|date',
                'start_date' => 'sometimes|date',
                'delivery_status' => 'sometimes|in:pending,in_progress,completed',
                'financial_closure_status' => 'sometimes|in:open,closed',
                'profit_or_loss_ratio' => 'nullable|numeric',
            ];
        }

        // For storing, all fields must be required
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'coordinates' => 'required|string',
            'commissioning_date' => 'required|date',
            'start_date' => 'required|date',
        ];
    }

    /**
     * Determine if the current request is for updating a site.
     *
     * @return bool
     */
    private function isUpdateRequest(): bool
    {
        return $this->isMethod('put') || $this->isMethod('patch');
    }
}
