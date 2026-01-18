<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'settings' => ['required', 'array'],
            'settings.check_in' => ['required', 'array'],
            'settings.check_in.interval_minutes' => [
                'required',
                'integer',
                'min:1',
                'max:10080',
            ],
            'settings.check_in.grace_period_minutes' => [
                'required',
                'integer',
                'min:0',
                'max:1440',
            ],
            'settings.notifications' => ['required', 'array'],
            'settings.notifications.enabled' => ['sometimes', 'boolean'],
            'settings.notifications.subject' => ['nullable', 'string', 'max:255'],
            'settings.notifications.message' => ['nullable', 'string', 'max:2000'],
            'settings.notifications.siblings' => [
                'required',
                'array',
                'min:1',
            ],
            'settings.notifications.siblings.*.name' => [
                'required',
                'string',
                'max:255',
            ],
            'settings.notifications.siblings.*.email' => [
                'required',
                'email',
                'max:255',
            ],
            'settings.data_handling' => ['required', 'array'],
            'settings.data_handling.purge_method' => [
                'required',
                Rule::in(['archive', 'delete']),
            ],
            'settings.data_handling.delay_minutes' => [
                'required',
                'integer',
                'min:0',
                'max:10080',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $settings = $this->input('settings', []);

        data_set(
            $settings,
            'notifications.enabled',
            filter_var(
                data_get($settings, 'notifications.enabled', false),
                FILTER_VALIDATE_BOOLEAN
            )
        );

        $this->merge([
            'settings' => $settings,
        ]);
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => __('Please enter your name.'),
            'name.max' => __('Your name may not be longer than 255 characters.'),

            'email.required' => __('Please enter your email address.'),
            'email.email' => __('Please enter a valid email address.'),
            'email.unique' => __('This email address is already in use.'),

            'settings.required' => __('Settings data is missing.'),

            'settings.check_in.required' => __('Check-in settings are required.'),
            'settings.check_in.interval_minutes.required' => __('Please set how often you will check in.'),
            'settings.check_in.interval_minutes.min' => __('Check-in interval must be at least 1 minute.'),
            'settings.check_in.interval_minutes.max' => __('Check-in interval is too large.'),

            'settings.check_in.grace_period_minutes.required' => __('Please set a grace period.'),
            'settings.check_in.grace_period_minutes.min' => __('Grace period cannot be negative.'),
            'settings.check_in.grace_period_minutes.max' => __('Grace period is too large.'),

            'settings.notifications.required' => __('Notification settings are required.'),

            'settings.notifications.siblings.required' => __('Please add at least one emergency contact.'),
            'settings.notifications.siblings.min' => __('At least one emergency contact is required.'),

            'settings.notifications.siblings.*.name.required' =>
                __('Each emergency contact must have a name.'),

            'settings.notifications.siblings.*.email.required' =>
                __('Each emergency contact must have an email address.'),

            'settings.notifications.siblings.*.email.email' =>
                __('One of the emergency contact emails is not valid.'),

            'settings.notifications.subject.max' =>
                __('Notification subject is too long.'),

            'settings.notifications.message.max' =>
                __('Notification message is too long.'),

            'settings.data_handling.required' =>
                __('Data handling settings are required.'),

            'settings.data_handling.purge_method.required' =>
                __('Please choose how your data should be handled.'),

            'settings.data_handling.purge_method.in' =>
                __('Selected data handling method is invalid.'),

            'settings.data_handling.delay_minutes.required' =>
                __('Please set a data purge delay.'),

            'settings.data_handling.delay_minutes.min' =>
                __('Purge delay cannot be negative.'),

            'settings.data_handling.delay_minutes.max' =>
                __('Purge delay is too large.'),
        ];
    }
}
