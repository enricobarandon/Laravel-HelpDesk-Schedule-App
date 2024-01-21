<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'bet_count' => ['required','integer'],
            'transaction_count' => ['required','integer'],
            'date_covered' => ['required', 'string', 'max:100'],
            'fg_link' => ['required', 'string'],
            'schedule_link' => ['required', 'string'],
            'start' => ['required', 'string', 'max:100'],
            'end' => ['required', 'string', 'max:100'],
            'duration' => ['required', 'string', 'regex:/^[a-z\d\-_ñÑ\s]+$/i', 'max:100'],
            'requested_by' => ['required', 'string', 'regex:/^[a-z\d\-_ñÑ\s]+$/i', 'max:100'],
            'processed_by' => ['required', 'string', 'regex:/^[a-z\d\-_ñÑ\s]+$/i', 'max:100'],
        ];
    }
}
