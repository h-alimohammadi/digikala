<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductWarrantyRequest extends FormRequest
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
            'warranty_id' => 'required',
            'price1' => 'required|numeric',
            'price2' => 'required|numeric',
            'product_number' => 'required|numeric',
        ];
        if (!empty($this->request->get('product_number'))) {
            $array['product_number'] = 'numeric';
        }
        if (!empty($this->request->get('product_number_cart'))) {
            $array['product_number_cart'] = 'numeric';
        }
        if (!empty($this->request->get('send_time'))) {
            $array['send_time'] = 'numeric';
        }
        return $array;
    }

    public function attributes()
    {
        return [
            'warranty_id' => 'گارانتی',
            'price1' => 'قیمت محصول',
            'price2' => 'قیمت محصول برای فروش',
            'product_number' => 'تعداد موجودی محصول',
            'product_number_cart' => 'تعداد سفارش در سبد خرید',
            'send_time' => 'زمان آماده سازی محصول',
        ];

    }

    protected function getValidatorInstance()
    {
        if ($this->request->get('price1')) {
            $this->merge(['price1' => str_replace(',', '', $this->request->get('price1'))]);
        }
        if ($this->request->get('price2')) {
            $this->merge(['price2' => str_replace(',', '', $this->request->get('price2'))]);
        }
        if ($this->request->get('product_number')) {
            $this->merge(['product_number' => str_replace(',', '', $this->request->get('product_number'))]);
        }
        if ($this->request->get('product_number_cart')) {
            $this->merge(['product_number_cart' => str_replace(',', '', $this->request->get('product_number_cart'))]);
        }
        if ($this->request->get('send_time')) {
            $this->merge(['send_time' => str_replace(',', '', $this->request->get('send_time'))]);
        }
        return parent::getValidatorInstance(); // TODO: Change the autogenerated stub
    }
}
