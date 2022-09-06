<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Offer;
use App\ProductWarranty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function incredible_offers()
    {
        return view('admin.incredible_offers');
    }

    public function getProductWarranty(Request $request)
    {
        $search_text = $request->get('search_text', '');

        $productWarranty = ProductWarranty::with(['color', 'warranty', 'product'])
            ->orderBy('offers', 'desc');
        $productWarranty = $productWarranty->whereHas('warranty');
        if (empty($search_text)){
            $productWarranty=$productWarranty->whereHas('product');
        }else{
            define('search_text',$search_text);
            $productWarranty=$productWarranty->whereHas('product',function (Builder $query){
                $query->where('title','LIKE','%'.search_text.'%');
            });
        }

            $productWarranty = $productWarranty->paginate(10);
        return $productWarranty;
    }

    public function addIncredibleOffers($id, Request $request)
    {
        $productWarranty = ProductWarranty::find($id);

        if ($productWarranty) {
            $offers = new Offer();
            $res = $offers->add($request, $productWarranty);
            return $res;
        } else
            return 'error';
    }

    public function removeIncredibleOffers($id)
    {

        $productWarranty = ProductWarranty::find($id);

        if ($productWarranty) {
            $offers = new Offer();
            $res = $offers->remove($productWarranty);
            return $res;
        } else
            return 'error';
    }

    public function filemanager()
    {
        return view('admin.filemanager');
    }
    public function upload(Request $request) {
        $file = $request->file('upload');
        $base_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $file_name = $base_name . '_' . time() . '.' . $ext;
        $file->storeAs('files/uploads/review/', $file_name, 'public_files');
        $function = $request->CKEditorFuncNum;
        $url = asset('files/uploads/review/' . $file_name);

        return response("<script>window.parent.CKEDITOR.tools.callFunction({$function}, '{$url}', 'فایل به درستی آپلود شد')</script>");
    }
}
