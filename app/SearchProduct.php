<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchProduct extends Model
{
    protected $category = [];
    protected $min_price = 0;
    protected $max_price = 0;
    protected $attribute;
    protected $sort = 21;
    protected $colors = null;
    public $brands = null;
    protected $string = null;
    protected $status = 0;
    protected $ready_to_shipment = 0;

    public function __construct($request)
    {
        $this->attribute = $request->get('attribute', null);
        $this->sort = $request->get('sortBy', 21);
        $this->colors = $request->get('color', null);
        $this->string = $request->get('string', null);
        $this->status = $request->get('has_product', 0);
        $this->ready_to_shipment = $request->get('ready_to_shipment', 0);
        $this->setMinAndMaxPrice($request->all());
    }

    public function setProductCategory($catList)
    {
        $this->category[$catList->id] = $catList->id;
        foreach ($catList->getChild as $key => $value) {
            $this->category[$value->id] = $value->id;
            foreach ($value->getChild as $key2 => $value2) {
                $this->category[$value2->id] = $value2->id;
            }
        }
        return $this->category;
    }
    public function setBrandCategory($catId)
    {
        if (is_array($catId)){
            $j=0;
            foreach ($catId as $k=>$v){
                $this->category[$j]=$v;
                $j++;
            }
            $category=Category::whereIn('parent_id',$catId)->get();
            foreach ($category as $key => $value) {
                $this->category[$j] = $value->id;
            }
            return $this->category;
        }
    }

    public function getProduct()
    {
        $product2 = Product::orderBy('price', 'DESC');
        $product = Product::select(['id', 'title', 'product_url', 'price', 'discount_price', 'special', 'image_url', 'brand_id', 'status']);
        if (is_array($this->category) && sizeof($this->category) > 0) {
            $product = $product->whereIn('cat_id', $this->category);
            $product2 = $product2->whereIn('cat_id', $this->category);
        }
        if (is_array($this->attribute)) {
            $product_id = $this->get_product_from_attribute();
            $product = $product->whereIn('id', $product_id);
        }
        if ($this->brands) {
            if (is_array($this->brands)) {
                $product = $product->whereIn('brand_id', $this->brands);
                $product2 = $product2->whereIn('brand_id', $this->brands);
            } else {
                $product = $product->where('brand_id', $this->brands);
                $product2 = $product2->where('brand_id', $this->brands);
            }
        }
        if ($this->colors) {
            define('colors', $this->colors);
            $product = $product->whereHas('getColor', function ($query) {
                $query->whereIn('color_id', colors);
            });
            if (is_array($this->colors)) {
                $product = $product->whereIn('brand_id', $this->colors);
                $product2 = $product2->whereIn('brand_id', $this->colors);
            } else {
                $product = $product->where('brand_id', $this->colors);
                $product2 = $product2->where('brand_id', $this->colors);
            }
        }
        if ($this->string) {
            $product = $product->where('title', 'Like', '%' . $this->string . '%');
        }
        if ($this->status == 1) {
            $product = $product->where('status', 1);
        }
        if ($this->ready_to_shipment == 1) {
            $product = $product->where('ready_to_shipment', 0);
        }

        if ($this->max_price != 0) {
            $product->where('price', '<=', $this->max_price);
        }
        if ($this->min_price > 0) {
            $product->where('price', '>=', $this->min_price);
        }
        $sort = $this->get_sort();
        $product = $product->orderBy($sort[0], $sort[1]);
        $count=$product->count();
        $product = $product->with(['getColor.color', 'getFirstProductPrice'])->paginate(12);

        $maxPrice = $product2->first();
        $maxPrice = $maxPrice ? $maxPrice->price : 0;
        return [
            'product' => $product,
            'maxPrice' => $maxPrice,
            'count' => $count,
        ];
    }

    private function setMinAndMaxPrice($data)
    {
        if (array_key_exists('price', $data)) {
            if (array_key_exists('min', $data['price'])) {
                $this->min_price = $data['price']['min'];
            }
            if (array_key_exists('max', $data['price'])) {
                $this->max_price = $data['price']['max'];
            }
        }
    }

    private function get_product_from_attribute()
    {
        $array_id = [];
        foreach ($this->attribute as $key => $value) {
            $data = ProductFilter::whereIn('filter_value', $value)->pluck('product_id', 'id')->toArray();
            $array_id[$key] = $data;
        }
        if (sizeof($array_id) > 1) {
            $product_id = call_user_func_array('array_intersect', $array_id);
        } else {
            $id = collect($array_id);
            $product_id = $id->values()->all()[0];
        }
        return $product_id;
    }

    public function get_sort()
    {
        $sort = [];
        $sort[21] = ['view', 'DESC'];
        $sort[22] = ['order_number', 'DESC'];
        $sort[23] = ['id', 'DESC'];
        $sort[24] = ['price', 'ASC'];
        $sort[25] = ['price', 'DESC'];
        if (array_key_exists($this->sort, $sort)) {
            return $sort[$this->sort];
        } else {
            return $sort[23];
        }
    }
}
