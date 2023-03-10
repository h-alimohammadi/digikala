<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
        ];
        if (!empty(trim($this->send_time))) {
            $rules['send_time'] = 'numeric';
        }
        if (!empty(trim($this->send_price))) {
            $rules['send_price'] = 'numeric';
        }
        if (!empty(trim($this->min_order_price))) {
            $rules['min_order_price'] = 'numeric';
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'نام شهر',
            'send_time' => 'زمان حدودی ارسال سفارش',
            'send_price' => 'هزینه ارسال سفارش',
            'min_order_price' => 'حداقل خرید برای ارسال رایگان',
        ];

    }

    protected function getValidatorInstance()
    {
        if ($this->request->get('send_time')) {
            $this->merge(['send_time' => str_replace(',', '', $this->request->get('send_time'))]);
        }
        if ($this->request->get('send_price')) {
            $this->merge(['send_price' => str_replace(',', '', $this->request->get('send_price'))]);
        }
        if ($this->request->get('min_order_price')) {
            $this->merge(['min_order_price' => str_replace(',', '', $this->request->get('min_order_price'))]);
        }
        return parent::getValidatorInstance();
    }
}
