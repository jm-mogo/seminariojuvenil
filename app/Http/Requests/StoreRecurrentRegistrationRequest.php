<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Import Rule if needed for complex rules

class StoreRecurrentRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * For now, we assume anyone can submit. Adjust if auth is needed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Based on the Vue component and requirements for recurrent students
        return [
            // Student Info
            'studentFirstName' => ['required', 'string', 'max:100'],
            'studentLastName' => ['required', 'string', 'max:100'],
            'studentIdNumber' => ['required', 'string', 'max:20'], // Add regex if specific format needed
            'birthDate' => ['required', 'date_format:Y-m-d'],
            'studentPhoneNumber' => ['nullable', 'string', 'max:20'], // Optional
            'studentEmail' => ['nullable', 'email', 'max:255'],      // Optional
            'studentGender' => ['required', Rule::in(['male', 'female'])],

            // === Recurrent Specific Field ===
            'previousParticipations' => ['required', 'integer', 'in:1,2'], // Must be 1 or 2

            // Guardian Info
            'guardianFirstName' => ['required', 'string', 'max:100'],
            'guardianLastName' => ['required', 'string', 'max:100'],
            'guardianIdNumber' => ['required', 'string', 'max:20'], // Add regex if specific format needed
            'guardianPhoneNumber' => ['required', 'string', 'max:20'], // Required for guardian
            'guardianRelationship' => ['required', 'string', 'max:100'],

            // Church Info
            'churchName' => ['required', 'string', 'max:150'],
            'churchCity' => ['required', 'string', 'max:100'], // Assuming this IS needed based on form
            'pastorName' => ['required', 'string', 'max:150'],
            'attendanceDuration' => ['required', 'string', 'max:50'],

            // Documents (Reduced set for recurrent)
            'profilePhoto' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Max 2MB
            'studentIdPhoto' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'], // Max 2MB
            'guardianIdPhoto' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'], // Max 2MB
            // testimony, purpose, recommendation are NOT required/validated here


        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        // Optional: Provide Spanish names for fields in error messages
        return [
            'studentFirstName' => 'nombres del estudiante',
            'studentLastName' => 'apellidos del estudiante',
            'studentIdNumber' => 'cédula del estudiante',
            'birthDate' => 'fecha de nacimiento',
            'studentPhoneNumber' => 'teléfono del estudiante',
            'studentEmail' => 'correo electrónico del estudiante',
            'studentGender' => 'género del estudiante',
            'previousParticipations' => 'participaciones anteriores',
            'guardianFirstName' => 'nombres del representante',
            'guardianLastName' => 'apellidos del representante',
            'guardianIdNumber' => 'cédula del representante',
            'guardianPhoneNumber' => 'teléfono del representante',
            'guardianRelationship' => 'parentesco',
            'churchName' => 'nombre de la iglesia',
            'churchCity' => 'ciudad de la iglesia',
            'pastorName' => 'nombre del pastor',
            'attendanceDuration' => 'tiempo asistiendo',
            'profilePhoto' => 'foto personal',
            'studentIdPhoto' => 'foto cédula del estudiante',
            'guardianIdPhoto' => 'foto cédula del representante',
            'studentAgreedToRules' => 'confirmación del estudiante',
            'guardianAgreedToRules' => 'confirmación del representante',
        ];
    }
}
