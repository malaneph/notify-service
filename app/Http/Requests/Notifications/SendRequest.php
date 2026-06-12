<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'priority' => 'required|in:high,medium,low',
            'channel' => 'required|in:sms,email',
            'recipients' => 'required|array',
            'message' => 'required|string',
            'idempotency_key' => 'sometimes|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
