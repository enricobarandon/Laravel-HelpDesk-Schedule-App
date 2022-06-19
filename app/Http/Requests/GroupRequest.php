<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if ($this->operation_date != "")
            $this->merge(['operation_date' => date('Y-m-d', strtotime($this->operation_date))]);

            
        if ($this->pullout_date != "")
        $this->merge(['pullout_date' => date('Y-m-d', strtotime($this->pullout_date))]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'active_staff' => ['required'],
            'installed_pc' => ['required'],
            'status' => ['nullable'],
            'operation_date' => ['required','date'],
            'pullout_date' => ['nullable','date']
        ];
    }
}
