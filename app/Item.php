<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Item extends Model
{

    protected $table = 'items';
    protected $fillable = ['category_id', 'title', 'position', 'show_item', 'parent_id'];

    public static function addItems($request, $category)
    {
        $items = $request->get('item', []);
//        self::where(['category_id' => $category->id, 'parent_id' => 0])->update(['position' => 0]);
        $count = 1;
        foreach ($items as $key => $item) {
            if (!empty($item)) {
                if ($key < 0) {
                    $id = self::insertGetId([
                        'category_id' => $category->id,
                        'title' => $item,
                        'position' => $count,
                    ]);
                    self::addChildItems($request, $key, $category->id, $id);

                } else {
                    self::where('id', $key)->update(['title' => $item, 'position' => $count]);
                    self::addChildItems($request, $key, $category->id, $key);
                }
                $count++;
            }
        }
    }

    private static function addChildItems($request, $key, $cat_id, $id)
    {
        //        self::where(['category_id' => $cat_id, 'parent_id' => $id])->update(['position' => 0]);
        $childItems = $request->get('child_item', []);
        if (array_key_exists($key, $childItems)) {
            $count1 = 1;
            foreach ($childItems[$key] as $key1 => $childItem) {
                $getValue = getShowValue($request, $key, $key1);
                if (!empty($childItem)) {
                    if ($key1 < 0) {
                        self::insert([
                            'category_id' => $cat_id,
                            'title' => $childItem,
                            'position' => $count1,
                            'show_item' => $getValue,
                            'parent_id' => $id
                        ]);
                    } else {
                        self::where('id', $key1)->update([
                            'title' => $childItem,
                            'position' => $count1,
                            'show_item' => $getValue,
                        ]);
                    }
                }
                $count1++;
            }
        }

    }

    public static function getProductItems($product)
    {
        define('product_id', $product->id);
        $category = Category::findOrFail($product->cat_id);
        $cate_id[0] = $product->cat_id;
        if ($category) {
            $cate_id[1] = $category->parent_id;
        }
        $items = self::with('getChild.getValue')->where('parent_id', 0)
            ->whereIn('category_id', $cate_id)->orderBy('position', 'ASC')->get();
        return $items;
    }

    public static function getProductItemsWithFilter($product)
    {
        define('product_id', $product->id);
        $category = Category::findOrFail($product->cat_id);
        $cate_id[0] = $product->cat_id;
        if ($category) {
            $cate_id[1] = $category->parent_id;
        }
        $items = self::with('getChild.getValue')->where('parent_id', 0)
            ->whereIn('category_id', $cate_id)->orderBy('position', 'ASC')->get();
        $filters = Filter::with('getChild')->where('parent_id', 0)->whereIn('category_id', $cate_id)->whereNotNull('item_id')->get();
        return ['items' => $items, 'filters' => $filters];
    }

    public static function getCategoryItems($categry)
    {
        $category = Category::findOrFail($categry->id);
        $cate_id[0] = $categry->id;
        if ($category) {
            $cate_id[1] = $category->parent_id;
        }
        $items = self::with('getChild')->where('parent_id', 0)
            ->whereIn('category_id', $cate_id)->orderBy('position', 'ASC')->get();
        return $items;
    }

    public static function addFilter($key, $product, $filter_value)
    {
        if (array_key_exists($key, $filter_value)) {
            foreach ($filter_value[$key] as $k => $value) {
                DB::table('product_filter')->where(['product_id' => $product->id, 'filter_id' => $k])->delete();
                $ex = explode('@', $value);
                foreach ($ex as $v) {
                  if (!empty($v)){
                      DB::table('product_filter')->insert([
                          'product_id' => $product->id,
                          'filter_id' => $k,
                          'filter_value' => $v,
                      ]);
                  }
                }
            }
        }
    }

    public function getChild()
    {
        return $this->hasMany(Item::class, 'parent_id', 'id')->orderBy('position', 'ASC');
    }

    public function getValue()
    {
        return $this->hasMany(ItemValue::class, 'item_id', 'id')
            ->where('product_id', product_id);
    }


}

