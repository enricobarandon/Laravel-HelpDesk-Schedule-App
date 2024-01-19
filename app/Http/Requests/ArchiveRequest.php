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
            'bet_count' => ['required'],
            'transaction_count' => ['required'],
            'date_covered' => ['required'],
            'fg_link' => ['required'],
            'schedule_link' => ['required'],
            'start' => ['required'],
            'end' => ['required'],
            'duration' => ['required'],
            'requested_by' => ['required'],
            'processed_by' => ['required'],
        ];
    }
}
