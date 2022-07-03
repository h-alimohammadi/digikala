<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends CustomController
{
    protected $model = 'Category';
    protected $title = 'دسته';
    protected $route_params = 'category';

    public function index(Request $request)
    {
        session_start();
        if (isset($_GET['page']))
            $_SESSION['page'] = '?page=' . $_GET['page'];
        //Category::with('getParent')->orderBy('id', 'Desc')->paginate(5);
        $categories = Category::getData($request->all());
        $trashed_cat_count = Category::onlyTrashed()->count();
        return view('category.index', compact('categories', 'trashed_cat_count','request'));
    }

    public function create()
    {
        $parent_category = Category::getParents();
        return view('category.create', compact('parent_category'));
    }

    public function store(CategoryRequest $request)
    {
        $notShow = $request->has('notShow') ? 1 : 0;
        $category = new Category($request->all());
        $category->notShow = $notShow;
        $category->url = get_url($request->get('ename'));
        $img_url = upload_file($request, 'img', 'category');
        $category->img = $img_url;
        $category->save();
        return redirect('admin/category')->with('message', 'ثبت دسته با موفقیت انجام شد.');
    }

    public function edit(Category $category)
    {
        $parent_category = Category::getParents();
        return view('category.edit', compact('category', 'parent_category'));
    }

    public function update(Category $category, CategoryRequest $request)
    {
        session_start();
        if (isset($_SESSION['page']))
            $page = $_SESSION['page'];
        else
            $page = '';
        $data = $request->all();
        $notShow = $request->has('notShow') ? 1 : 0;
        $category->url = get_url($request->get('ename'));
        $img_url = upload_file($request, 'img', 'category');
        if ($img_url != null) {
            $data['img'] = $img_url;
            remove_file($category->img, 'category/');
        }
        $data['notShow'] = $notShow;

        $category->update($data);
        return redirect('admin/category' . $page)->with('message', 'ویرایش دسته با موفقیت انجام شد.');
    }


}

