<?php

namespace Luchavez\SimpleMaintenance\Http\Requests\MaintenanceLog;

use Luchavez\StarterKit\Requests\FormRequest;

/**
 * Class UpdateMaintenanceLogRequest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class UpdateMaintenanceLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|max:255',
            'description' => 'nullable|max:255',
            'tags' => 'exclude|required|array',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
