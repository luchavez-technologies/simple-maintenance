<?php

namespace Luchavez\SimpleMaintenance\Http\Requests;

use Luchavez\StarterKit\Requests\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class ToggleMaintenanceModeRequest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ToggleMaintenanceModeRequest extends FormRequest
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
            'is_down' => 'boolean',
            'title' => 'nullable|max:255',
            'description' => 'nullable|max:255',
            'secret' => 'nullable|prohibited_if:is_down,false|max:255',
            'scheduled_at' => 'nullable|prohibited_if:is_down,false|after_or_equal:'.simpleMaintenance()->getMinimumScheduledAt(),
            'announce_at' => 'exclude|nullable|before_or_equal:scheduled_at',
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
        $merge = [];
        $is_under_maintenance = app()->isDownForMaintenance();

        if (! $this->has('is_down')) {
            $merge['is_down'] = ! $is_under_maintenance;
        }

        if (! $is_under_maintenance) {
            $secret = $this->get('secret') ?? Str::random();
            $merge['secret'] = Str::slug($secret);
        }

        if ($schedule = $this->get('scheduled_at')) {
            $merge['scheduled_at'] = Carbon::parse($schedule);
        }

        if ($announce = $this->get('announce_at')) {
            $merge['announce_at'] = Carbon::parse($announce);
        }

        $this->merge($merge);
    }
}
