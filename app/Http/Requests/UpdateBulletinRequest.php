<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBulletinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by VerifyEditToken middleware
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'status' => 'required|in:published,archived',
            'has_forum' => 'boolean',
            'has_shifts' => 'boolean',
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
            'title.required' => 'Der Titel ist erforderlich.',
            'title.max' => 'Der Titel darf maximal 255 Zeichen lang sein.',
            'description.required' => 'Die Beschreibung ist erforderlich.',
            'start_at.date' => 'Das Startdatum muss ein gültiges Datum sein.',
            'end_at.date' => 'Das Enddatum muss ein gültiges Datum sein.',
            'end_at.after_or_equal' => 'Das Enddatum muss nach oder am Startdatum liegen.',
            'location.required' => 'Der Ort ist erforderlich.',
            'location.max' => 'Der Ort darf maximal 255 Zeichen lang sein.',
            'contact_name.required' => 'Der Kontaktname ist erforderlich.',
            'contact_name.max' => 'Der Kontaktname darf maximal 255 Zeichen lang sein.',
            'contact_phone.max' => 'Die Telefonnummer darf maximal 50 Zeichen lang sein.',
            'contact_email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
            'contact_email.max' => 'Die E-Mail-Adresse darf maximal 255 Zeichen lang sein.',
            'status.required' => 'Der Status ist erforderlich.',
            'status.in' => 'Der Status muss entweder "veröffentlicht" oder "archiviert" sein.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_forum' => $this->has('has_forum'),
            'has_shifts' => $this->has('has_shifts'),
        ]);
    }
}
