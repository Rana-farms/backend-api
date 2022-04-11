<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'name' =>  [ 'required', 'string', 'max:255'],
            'file' => ['file', 'mimes:doc,docx,pdf,txt,csv,xls,xlsx', 'max:4048'],
        ];
    }
}
