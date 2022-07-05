<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $table = 'filters';
    protected $fillable = ['category_id', 'title', 'parent_id', 'position', 'item_id'];

    public static function addFilters($request, $category)
    {
//        self::where(['category_id' => $category->id, 'parent_id' => 0])->update(['position' => 0]);
        $filters = $request->get('filter', []);
        $itemValue = $request->get('item_id', []);
        $count = 1;
        foreach ($filters as $key => $item) {
            if (!empty($item)) {
                $item_id = array_key_exists($key, $itemValue) ? $itemValue[$key] : 0;
                if ($key < 0) {
                    $id = self::insertGetId([
                        'category_id' => $category->id,
                        'title' => $item,
                        'position' => $count,
                        'item_id' => $item_id
                    ]);
                    self::addChildItems($request, $key, $category->id, $id);

                } else {
                    self::where('id', $key)->update(['title' => $item, 'position' => $count, 'item_id' => $item_id]);
                    self::addChildItems($request, $key, $category->id, $key);
                }
                $count++;
            }
        }
    }

    private static function addChildItems($request, $key, $cat_id, $id)
    {
        //        self::where(['category_id' => $cat_id, 'parent_id' => $id])->update(['position' => 0]);
        $childFilters = $request->get('child_filter', []);
        if (array_key_exists($key, $childFilters)) {
            $count1 = 1;
            foreach ($childFilters[$key] as $key1 => $childFilter) {
                if (!empty($childFilter)) {
                    if ($key1 < 0) {
                        self::insert([
                            'category_id' => $cat_id,
                            'title' => $childFilter,
                            'position' => $count1,
                            'parent_id' => $id
                        ]);
                    } else {
                        self::where('id', $key1)->update([
                            'title' => $childFilter,
                            'position' => $count1,
                        ]);
                    }
                }
                $count1++;
            }
        }

    }

    public static function getProductFilters($product)
    {
        define('product_id', $product->id);
        $category = Category::findOrFail($product->cat_id);
        $cate_id[0] = $product->cat_id;
        if ($category) {
            $cate_id[1] = $category->parent_id;
        }
        $items = self::with(['getChild', 'getValue'])->where('parent_id', 0)
            ->whereIn('category_id', $cate_id)->orderBy('position', 'ASC')->get();
        return $items;
    }

    public function getChild()
    {
        return $this->hasMany(Filter::class, 'parent_id', 'id')->orderBy('position', 'ASC');
    }

    public function getValue()
    {
        return $this->hasMany(ProductFilter::class, 'filter_id', 'id')
            ->where('product_id', product_id);
    }
}
