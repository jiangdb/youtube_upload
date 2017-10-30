<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserPost extends FormRequest
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
    }

    /**
     * 自定义字段未验证通过的文字提示信息
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '请输入用户昵称',
            'name.max'      => '用户昵称长度不能大于255个字节',
            'email.required' => '请输入用户邮箱',
            'email.email'    => '请输入有效的邮箱地址',
            'email.max'    => '邮箱地址长度不能大于255个字节',
            'email.unique'    => '当前用户邮箱地址已存在',
            'password.required'    => '请输入密码',
            'password.min'    => '密码最小的长度不能少于6位',
            'password.confirmed'    => '密码和确认密码不一致'
        ];
    }

}
