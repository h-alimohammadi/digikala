<?php

namespace App\Http\Controllers\Admin;

use App\Brand;
use App\Category;
use App\Color;
use App\Filter;
use App\Http\Requests\ProductRequest;
use App\Item;
use App\Product;
use App\ProductFilter;
use App\ProductGallery;
use Illuminate\Http\Request;
use DB;

class ProductController extends CustomController
{
    protected $model = 'Product';
    protected $title = 'محصول';
    protected $route_params = 'product';

    public function index(Request $request)
    {
        $products = Product::getData($request->all());
        $trashed_product_count = Product::onlyTrashed()->count();
        $status = Product::productStatus();
        return view('product.index', compact('products', 'trashed_product_count', 'request', 'status'));
    }

    public function create()
    {
        $colors = Color::get();
        $brand [''] = 'انتخاب برند';
        $brand = array_merge($brand, Brand::pluck('name', 'id')->toArray());
        $parent_category = Category::getParents2();
        $status = Product::productStatus();
        return view('product.create', compact('parent_category', 'brand', 'colors', 'status'));
    }

    public function store(Request $request)
    {
        $product_color = $request->get('product_color', []);
        $product = new Product($request->all());
        $product->use_for_gift_cart = $request->has('use_for_gift_cart') ? 'yes' : 'no';
        $product->product_url = get_url($request->get('title'));
        $img = upload_file($request, 'pic', 'products');
        $product->image_url = $img;
        create_fit_pic('files/uploads/products/' . $img, $img);
        $product->saveOrFail();
        foreach ($product_color as $color) {
            DB::table('product_color')->insert([
                'color_id' => $color,
                'product_id' => $product->id,
                'cat_id' => $product->cat_id,
            ]);
        }

        set_cat_brand(null,$product);
        return redirect('admin/product')->with('message', 'ثبت محصول با موفقیت انجام شد.');
    }

    public function edit(Product $product)
    {
        $colors = Color::get();
        $brand [''] = 'انتخاب برند';
        $brand = array_merge($brand, Brand::pluck('name', 'id')->toArray());
        $parent_category = Category::getParents2();
        $status = Product::productStatus();
        $productColor = DB::table('product_color')->where('product_id', $product->id)->pluck('id', 'color_id')->toArray();
        return view('product.edit', compact('product', 'parent_category', 'brand', 'colors', 'status', 'productColor'));
    }

    public function update(Product $product, ProductRequest $request)
    {
        $oldData = [
            'cat_id'=>$product->cat_id,
            'brand_id'=>$product->brand_id,
        ];
        $product_color = $request->get('product_color', []);
        $data = $request->all();
        $data['use_for_gift_cart'] = $request->has('use_for_gift_cart') ? 'yes' : 'no';

        $data['product_url'] = get_url($request->get('title'));
        $img = upload_file($request, 'pic', 'products');
        if ($img != null) {
            remove_file($product->image_url, 'products/');
            remove_file($product->image_url, 'thumbnails/');
            $data['image_url'] = $img;
            create_fit_pic('files/uploads/products/' . $img, $img);
        }
        DB::table('product_color')->where('product_id', $product->id)->delete();
        foreach ($product_color as $color) {
            DB::table('product_color')->insert([
                'color_id' => $color,
                'product_id' => $product->id,
                'cat_id' => $product->cat_id,
            ]);
        }
        $product->update($data);

        set_cat_brand($oldData,$product);
        return redirect('admin/product')->with('message', 'ویرایش محصول با موفقیت انجام شد.');
    }

    public function gallery($id)
    {
        $product = Product::select(['id', 'title'])->where('id', $id)->firstOrFail();
        $productGalleries = ProductGallery::where('product_id', $product->id)->orderBy('position', 'ASC')->get();
        return view('product.gallery', compact('product', 'productGalleries'));
    }

    public function galleryUpload($id, Request $request)
    {
        $product = Product::select(['id'])->where('id', $id)->firstOrFail();
        if ($product) {
            $count = DB::table('product_galleries')->where('product_id', $product->id)->count();
            $image_url = upload_file($request, 'file', 'products/gallerys', 'image_' . $id . rand(1, 100));
            if ($image_url != null) {
                $count++;
                DB::table('product_galleries')->insert([
                    'product_id' => $id,
                    'image_url' => $image_url,
                    'position' => $count,
                ]);
                return 1;
            } else
                return 0;
        } else {
            return 0;
        }
    }

    public function removeImageGallery($id)
    {
        $productGallery = ProductGallery::findOrFail($id);
        remove_file($productGallery->image_url, 'products/gallerys/');
        $productGallery->delete();
        return redirect('admin/product/gallery/' . $productGallery->product_id)->with('message', 'حذف تصویر با موفقیت انجام شد.');

    }

    public function changeImagePosition($id, Request $request)
    {

//        return $id;
//        return explode(',',$request->get('parameters'));
        $i = 1;
        $ids = explode(',', $request->get('parameters'));
        foreach ($ids as $value) {
            $productGallery = ProductGallery::where(['id' => $value, 'product_id' => $id])->first();
            $productGallery->update(['position' => $i]);
            $i++;
        }
        return 'yes';
    }

    public function items($id)
    {
        $product = Product::where('id', $id)->select(['id', 'title', 'cat_id'])->firstOrFail();
        $data = Item::getProductItemsWithFilter($product);
        $productItems = $data['items'];
        $productFilters = $data['filters'];
        $product_filter = ProductFilter::where('product_id', $product->id)->pluck('filter_id', 'filter_value')->toArray();
        return view('product.item', compact('product', 'productItems', 'productFilters', 'product_filter'));
    }

    public function addItems($id, Request $request)
    {
//        return $request;
        $product = Product::where('id', $id)->select(['id'])->firstOrFail();
        DB::table('item_value')->where('product_id', $id)->delete();
        $items = $request->get('item_value', []);
        $filter_value = $request->get('filter_value', []);
        foreach ($items as $key => $item) {
            foreach ($item as $value) {
                if (!empty($value)) {
                    DB::table('item_value')->insert([
                        'product_id' => $product->id,
                        'item_id' => $key,
                        'item_value' => $value,
                    ]);
                }
            }
            Item::addFilter($key, $product, $filter_value);
        }
        return redirect()->back()->with('message', 'ثبت مشخصات فنی با موفقیت انجام شد.');
    }

    public function filters($id)
    {
        $product = Product::where('id', $id)->select(['id', 'title', 'cat_id'])->firstOrFail();
        $productFilters = Filter::getProductFilters($product);
        return view('product.filter', compact('product', 'productFilters'));
    }

    public function addFilters($id, Request $request)
    {
        $product = Product::where('id', $id)->select(['id'])->firstOrFail();
        DB::table('product_filter')->where('product_id', $id)->delete();
        $filters = $request->get('filter_value', []);
        if (is_array($filters)) {
            foreach ($filters as $key => $filter) {
                if (is_array($filter)) {
                    foreach ($filter as $value) {
                        if (!empty($value)) {
                            DB::table('product_filter')->insert([
                                'product_id' => $product->id,
                                'filter_id' => $key,
                                'filter_value' => (int)$value,
                            ]);
                        }
                    }
                }

            }
        }

        return redirect()->back()->with('message', 'ثبت فیلتر ها با موفقیت انجام شد.');
    }
}
