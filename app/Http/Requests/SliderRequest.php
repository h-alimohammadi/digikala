<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
        $array = [
            'title' => 'required',
            'url' => 'required|url',
        ];
        if ($this->isMethod('PUT')) {
            if (!empty($this->request->get('pic')))
                $array['pic'] = 'image';
        } else
            $array['pic'] = 'required|image';


        if (!empty($this->request->get('mobile_pic'))) {
            $array['mobile_pic'] = 'image';
        }
        return $array;
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان',
            'url' => 'آدرس (url)',
            'pic' => 'تصویر',
            'mobile_pic' => 'تصویر موبایل'
        ];
    }
}
