<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileServerPost extends FormRequest
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
            'filename' => 'required|unique:file_record|max:255',
            'csv_path' => 'required|file:csv'
        ];
    }

    /**
     *
     */
    public function messages()
    {
        return [
            'filename.required' => '文件名称不能为空',
            'filename.unique'   => '文件名称已存在',
            'filename.max'      => '文件名称长度不能大于255个字节',
            'csv_path.required' => '请上传视频csv文件',
            'csv_path.mimes'    => '上传的文件必须是csv格式'
        ];
    }
}
