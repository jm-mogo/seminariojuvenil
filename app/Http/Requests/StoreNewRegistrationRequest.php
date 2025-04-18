<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set to true to allow anyone to submit the form
        // Add specific authorization logic if needed (e.g., check if registration is open)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Max file sizes in kilobytes (KB)
        $maxPhotoSize = 2 * 1024; // 2MB
        $maxDocumentSize = 5 * 1024; // 5MB

        return [
            // Student
            'studentFirstName' => ['required', 'string', 'max:150'],
            'studentLastName' => ['required', 'string', 'max:150'],
            'studentIdNumber' => ['required', 'string', 'max:50'], // Adjust max based on format
            'birthDate' => ['required', 'date_format:Y-m-d'],
            // --- Add student_gender to your frontend form ---
            'studentGender' => ['required', Rule::in(['male', 'female'])], // Assuming 'male' or 'female' are the values sent
            'studentPhoneNumber' => ['nullable', 'string', 'max:25'], // Optional
            'studentEmail' => ['nullable', 'email', 'max:255'], // Optional

            // Guardian
            'guardianFirstName' => ['required', 'string', 'max:150'],
            'guardianLastName' => ['required', 'string', 'max:150'],
            'guardianIdNumber' => ['required', 'string', 'max:50'], // Field exists in form, required here
            'guardianPhoneNumber' => ['required', 'string', 'max:25'],
            'guardianRelationship' => ['required', 'string', 'max:100'],

            // Church
            'churchName' => ['required', 'string', 'max:255'],
            'churchCity' => ['required', 'string', 'max:150'], // Field exists in form, required here
            'pastorName' => ['required', 'string', 'max:255'],
            'attendanceDuration' => ['required', 'string', 'max:50'],

            // Documents (adjust mimes and max sizes as needed)
            'profilePhoto' => ['required', 'file', 'mimes:jpg,jpeg,png', "max:{$maxPhotoSize}"],
            'studentIdPhoto' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', "max:{$maxPhotoSize}"],
            'guardianIdPhoto' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', "max:{$maxPhotoSize}"],
            'salvationTestimony' => ['required', 'file', 'mimes:doc,docx,pdf', "max:{$maxDocumentSize}"],
            'enrollmentPurpose' => ['required', 'file', 'mimes:doc,docx,pdf', "max:{$maxDocumentSize}"],
            'recommendationLetter' => ['nullable', 'file', 'mimes:doc,docx,pdf', "max:{$maxPhotoSize}"], // Optional

        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        // Optional: Customize messages, especially for file uploads or complex rules
        return [
            'studentGender.required' => 'Por favor, selecciona el género del estudiante.',
            'studentGender.in' => 'El género seleccionado no es válido.',
            'birthDate.date_format' => 'El formato de la fecha de nacimiento debe ser Año-Mes-Día (YYYY-MM-DD).',
            'profilePhoto.max' => 'La foto personal no debe exceder los 2MB.',
            // Add other custom messages as needed
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        // Optional: Make error messages more user-friendly
        return [
            'studentFirstName' => 'nombres del estudiante',
            'studentLastName' => 'apellidos del estudiante',
            'studentIdNumber' => 'cédula del estudiante',
            'birthDate' => 'fecha de nacimiento',
            'studentGender' => 'género del estudiante',
            'studentPhoneNumber' => 'teléfono del estudiante',
            'studentEmail' => 'correo del estudiante',
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
            'studentIdPhoto' => 'foto cédula estudiante',
            'guardianIdPhoto' => 'foto cédula representante',
            'salvationTestimony' => 'testimonio de salvación',
            'enrollmentPurpose' => 'propósito de ingreso',
            'recommendationLetter' => 'carta de recomendación',
        ];
    }
}
