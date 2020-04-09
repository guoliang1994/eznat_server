<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Map extends FormRequest
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
            'remote_port' => 'required|numeric|notIn:80,443|min:1025|max:65535',
            'local_ip' => 'required',
            'local_port' => 'required|numeric|max:65535',
            'client_id' => 'required|numeric',
        ];
    }
    /**
     * 获取验证错误的自定义属性。
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => '映射名称',
            'remote_port' => '远程端口',
            'local_ip' => '本地ip',
            'local_port' => '本地端口',
            'client_id' => '客户端',
        ];
    }
    public function messages()
    {
       return [
           'remote_port.not_in' => '端口不能指定为80和443端口'
       ];
    }
}
