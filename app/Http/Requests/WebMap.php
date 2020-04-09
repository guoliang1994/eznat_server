<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebMap extends FormRequest
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
            'client_id' => 'required|numeric',
            'domain' => 'required',
            'local_ip' => 'required',
            'local_port' => 'required|numeric|max:65535',
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
            'domain' => '域名',
            'local_ip' => '本地ip',
            'local_port' => '本地端口',
        ];
    }
}
