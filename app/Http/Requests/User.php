<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
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
        return [
            'name' => 'required|max:32',
            'password' => 'required|max:32',
            'account' => 'required|max:64|alpha_num',
            'mobile' => 'regex:/^1[345789][0-9]{9}$/'
        ];
    }

    /**
     * 获取验证错误的自定义属性。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'mobile.regex' => '手机号有误'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'account' => '账号',
            'password' => '密码',
            'mobile' => '手机号码',
        ];
    }
}
