<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must be authenticated
        if (!auth()->check()) {
            return false;
        }

        // Check rate limiting
        $key = 'post-' . $this->ip();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return false;
        }

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
            'body' => 'required|string|max:2000',
            // Honeypot fields should be empty
            'website' => 'nullable|max:0',
            'email_confirm' => 'nullable|max:0',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'body.required' => 'Bitte geben Sie einen Text ein.',
            'body.max' => 'Der Beitrag darf maximal 2000 Zeichen lang sein.',
            'website.max' => 'Spam-Schutz aktiviert.',
            'email_confirm.max' => 'Spam-Schutz aktiviert.',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        if (!auth()->check()) {
            redirect()->route('login')
                ->with('error', 'Bitte melden Sie sich an, um einen Beitrag zu verfassen.')
                ->send();
            exit;
        }

        if (RateLimiter::tooManyAttempts('post-' . $this->ip(), 1)) {
            redirect()->back()
                ->withErrors(['rate_limit' => 'Bitte warten Sie 30 Sekunden vor dem nächsten Beitrag.'])
                ->send();
            exit;
        }
    }
}