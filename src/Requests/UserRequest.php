<?php

namespace Blublog\Blublog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (\Auth::user()->cannot('blublog_create_users')) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:250',
            'email' => 'required|email|unique:' . config('blublog.userModel') . ',email',
            'password' => 'required|min:8|max:150',
        ];
    }
}
