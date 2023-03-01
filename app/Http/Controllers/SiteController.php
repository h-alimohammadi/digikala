<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Cart;
use App\CatBrand;
use App\Category;
use App\Color;
use App\Comment;
use App\Item;
use App\ItemValue;
use App\Lib\Mobile_Detect;
use App\Product;
use App\ProductWarranty;
use App\Review;
use App\SearchProduct;
use App\Slider;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;
use Session;

class SiteController extends Controller
{
    protected $view='';
    public function __construct()
    {
        getCatList();
        $detect=new Mobile_Detect();
        if ($detect->isMobile() || $detect->isTablet()){
            $this->view='mobile/';
        }
    }

    public function index()
    {
        $sliders = Slider::orderBy('id', 'desc')->get();
        $productWarranty = ProductWarranty::with('product.category')
            ->with(['itemValue' => function ($query) {
                $query->with('importantItem')->whereHas('importantItem')->limit(2);
            }])
            ->where('offers', 1)->limit(10)->get()->unique('product_id');
        $newProduct = Product::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
        $bestSellingProduct = Product::where('status', 1)->orderBy('order_number', 'DESC')->limit(10)->get();
        $randomProduct=Product::where('status',1)->inRandomOrder()->limit(10)->select(['id','title','price','image_url','status','product_url','discount_price'])->get();
        return view($this->view.'Shop.index', compact('sliders', 'productWarranty', 'newProduct', 'bestSellingProduct','randomProduct'));
    }

    public function showProduct($product_id, $product_url = null)
    {
        $id = str_replace('dkp-', '', $product_id);
        $where = ['id' => $id];
        if ($product_url != null) {
            $where['product_url'] = $product_url;
        }
        $product = Product::with(['brand', 'category', 'getColor.color', 'productWarranties.warranty','ProductGallery'])->where($where)->firstOrFail();
        $productItems = Item::getProductItems($product);
        $productItemCount = ItemValue::where('product_id', $product->id)->count();
        $relateProducts = Product::where(['cat_id' => $product->cat_id, 'brand_id' => $product->brand_id])->where('id', '!=', $product->id)->get();
        $review = Review::where('product_id', $product->id)->get();
//        return $productItems;
        return view($this->view.'Shop.show_product', compact('product', 'productItems', 'productItemCount', 'relateProducts', 'review'));

    }

    public function changeColor(Request $request)
    {
        $color_id = $request->get('color_id');
        $product_id = $request->get('product_id');
        $product = Product::with('getColor.color', 'productWarranties.warranty')->where('id', $product_id)->first();
        $checkHasColor = ProductWarranty::where(['color_id' => $color_id, 'product_id' => $product_id])->where('product_number', '>', 0)->first();
        if ($product && $checkHasColor)
            return view('Include.warranty', compact('product', 'color_id'));
        else
            return false;
    }

    public function confirm()
    {
        if (Session::has('mobile_number')) {
            return view('auth.confirm');
        } else {
            return redirect('/');
        }
    }

    public function resend(Request $request)
    {
        $active_code = rand(99999, 1000000);
        $mobile = $request->get('mobile');
        if ($request->ajax()) {
            $user = User::where(['mobile' => $mobile, 'account_status' => 'InActive'])->first();
            if ($user) {
                $user->active_code = $active_code;
                $user->update();
                return 'Ok';
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }

    }

    public function activeAccount(Request $request)
    {
        $mobile = $request->get('mobile');
        $active_code = $request->get('active_code');
        $user = User::where(['mobile' => $mobile, 'active_code' => $active_code, 'account_status' => 'InActive'])->first();
        if ($user) {
            $user->account_status = 'active';
            $user->active_code = '';
            $user->update();
            Auth::guard()->login($user);
            return redirect('/');
        } else {
            return redirect()->back()->with('mobile_number', $mobile)
                ->with('validate_error', 'کد وارد شده اشتباه است.')->withInput();
        }
    }

    public function addCart(Request $request)
    {
        Cart::addCart($request);
        return redirect('/Cart');
    }

    public function showCart()
    {
        $cartData = Cart::getCartData();
        return view($this->view.'shop.cart', compact('cartData'));
    }

    public function removeProduct(Request $request)
    {
        return Cart::removeProduct($request);
    }

    public function changeProductCart(Request $request)
    {
        return Cart::changeProductCart($request);
    }

    public function showChildCatList($car_url, Request $request)
    {
        $category = Category::with('getChild.getChild.getChild')->where('url', $car_url)->firstOrFail();
        return view('Shop.child_cat', compact('category'));
    }

    public function CatProduct($car_url, Request $request)
    {
        $category = Category::with('getChild.getChild')
            ->with(['getChild' => function ($query) {
                $query->whereNull('search_url');
            }])->where('url', $car_url)->firstOrFail();
        $filters = Category::getCatFilter($category);
        $brands = CatBrand::with('getBrand')->where('cat_id', $category->id)->get();
        $colors = [];
        $checkHasColor = DB::table('product_color')->where('cat_id', $category->id)->first();
        if ($checkHasColor) {
            $colors = Color::get();
        }
        return view('Shop.cat_product', compact('filters', 'category', 'brands', 'colors'));
    }

    public function getCatProduct($car_url, Request $request)
    {
        $searchProduct = new SearchProduct($request);
        $category = Category::with('getChild.getChild')->where('url', 'cellphone')->firstOrFail();
        $searchProduct->setProductCategory($category);
        $searchProduct->brands = $request->get('brand', null);

        $catList = $searchProduct->setProductCategory($category);
        return $searchProduct->getProduct();
    }

    public function brandProduct($brand_name)
    {
        $brand = Brand::where('ename', $brand_name)->firstOrFail();
        if ($brand) {
            return view('Shop.brand_product', compact('brand'));
        }
    }

    public function getBrandProduct(Request $request, $brand_name)
    {
        $brand = Brand::with('getCat.category')->where('ename', $brand_name)->firstOrFail();
        $searchProduct = new SearchProduct($request);
        $searchProduct->brands = $brand->id;
        $catList = $searchProduct->setBrandCategory($request->get('category', []));
        return $searchProduct->getProduct();


    }

    public function Compare($id_product1, $product_id2 = null, $product_id3 = null, $product_id4 = null)
    {
        $items = [];
        $ids = return_id_product([$id_product1, $product_id2, $product_id3, $product_id4]);

        $products = Product::select(['id', 'title', 'product_url', 'cat_id', 'price'])->with(['getItemValue', 'ProductGallery'])->whereIn('id', $ids)->get();
        if (sizeof($products) > 0) {
            $items = Item::getCategoryItems($products[0]->cat_id);
            $category = Category::where('id', $products[0]->cat_id)->firstOrFail();
            return view('Shop.compare', compact('items', 'products', 'category'));
        } else {
            return redirect('/');
        }
    }

    public function getCompareProducts(Request $request)
    {
        $brand_id = $request->get('brand_id', 0);
        $cat_id = $request->get('cat_id', 0);
        $search_product = $request->get('search_product', '');
        $product = Product::where('cat_id', $cat_id)->select(['id', 'price', 'image_url', 'title']);
        if ($brand_id > 0) {
            $product = $product->where('brand_id', $brand_id);
        }
        if ($search_product != '') {
            $product = $product->where('title', 'LIKE', '%' . $search_product . '%');
//            $product = $product->where('ename','LIKE', '%'.$search_product.'%');

        }
        $product = $product->orderBy('order_number', 'DESC')->paginate(10);
        return $product;
    }

    public function getCatBrand(Request $request)
    {
        $cat_id = $request->get('cat_id', 0);
        $brands = CatBrand::with('getBrand')->where('cat_id', $cat_id)->get();
        return $brands;
    }

    public function commentForm(Product $product)
    {
        return view('Shop.comment_form', compact('product'));
    }

    public function addComment(Product $product, Request $request)
    {
        $status = Comment::addComment($request, $product);
        return redirect('product/dkp-' . $product->id . '/' . $product->product_url)->with('comment_status', $status['status']);

    }

    public function CartProductData()
    {
        return Cart::getCartData();
    }




}
