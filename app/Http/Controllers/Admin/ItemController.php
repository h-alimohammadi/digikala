<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function items(Category $category)
    {
        $items = Item::with('getChild')->where(['category_id' => $category->id, 'parent_id' => 0])->orderBy('position','ASC')->get();
        return view('item.index', compact('category','items'));
    }

    public function addItems($id, Request $request)
    {
        $category = Category::findOrFail($id);
        Item::addItems($request, $category);
        return redirect()->back()->with('message', 'ثبت مشخصات فنی با موفقیت انجام شد.');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->getChild()->delete();
        $item->delete();
        return redirect()->back()->with('message', 'حذف مشخصات فنی با موفقیت انجام شد.');

    }
}
