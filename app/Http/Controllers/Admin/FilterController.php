<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Filter;
use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function filters(Category $category)
    {
        $filters = Filter::with('getChild')->where(['category_id' => $category->id, 'parent_id' => 0])->orderBy('position','ASC')->get();
        $items = Item::getCategoryItems($category);
        return view('filter.index', compact('category','filters','items'));
    }

    public function addFilters($id, Request $request)
    {
        $category = Category::findOrFail($id);
        Filter::addFilters($request, $category);
        return redirect()->back()->with('message', 'ثبت فیلترها با موفقیت انجام شد.');
    }

    public function destroy($id)
    {
        $item = Filter::findOrFail($id);
        $item->getChild()->delete();
        $item->delete();
        return redirect()->back()->with('message', 'حذف فیلترها با موفقیت انجام شد.');

    }
}
