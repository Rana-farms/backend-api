<?php

namespace App\Http\Requests;
use App\Models\User;
use App\Models\Investor;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvestorProfile extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $investor = Investor::where('user_id', auth()->user()->id)->first();

        return [
            'fullname' =>  [ 'required', 'string', 'max:255'],
            'address' =>  ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits:11', Rule::unique('investors')->ignore($investor->id)],
        ];
    }
}
