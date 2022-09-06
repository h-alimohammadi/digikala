<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomController extends Controller
{

    public function destroy($id)
    {
        $query_string = property_exists($this,'queryString') ? '&' . $this->queryString : '';
        $model_name = '\\App\\' . $this->model;
//        session_start();
//        if (isset($_SESSION['page']))
//            $page = $_SESSION['page'];
//        else
//            $page = '';
        $row = $model_name::withTrashed()->findOrFail($id);


        if ($row->deleted_at == null) {
            $row->delete();
            $message = "$this->title انتخاب شده به سطل زباله انتقال یافت.";
        } else {
            $row->forceDelete();
            $message = "$this->title انتخاب شده با موفقیت حذف شد";
        }
        return redirect('admin/' . $this->route_params . '?trashed=true' .$query_string)->with('message', $message);
    }

    public function remove_items(Request $request)
    {
//        return $request;
        $model_name = '\\App\\' . $this->model;
        $query_string = property_exists($this,'queryString') ? '&' . $this->queryString : '';

        if (isset($this->route_params2)){
            $ids = $request->get('id_' . $this->route_params2, []);
        }else{
            $ids = $request->get('id_' . $this->route_params, []);

        }
        foreach ($ids as $id) {
            $row = $model_name::withTrashed()->findOrFail($id);
            if ($row->deleted_at == null) {
                $row->delete();
                $message = "$this->title های انتخاب شده به سطل زباله انتقال یافت.";

            } else {
                $row->forceDelete();
                $message = "$this->title های انتخاب شده با موفقیت حذف شد";

            }

        }
        return redirect('admin/' . $this->route_params . '?trashed=true' . $query_string)->with('message', $message);
    }

    public function restore_items(Request $request)
    {
        $model_name = '\\App\\' . $this->model;
        $query_string = property_exists($this,'queryString') ? '?' . $this->queryString : '';

        if (isset($this->route_params2)){
            $ids = $request->get('id_' . $this->route_params2, []);
        }else{
            $ids = $request->get('id_' . $this->route_params, []);

        }

        foreach ($ids as $id) {
            $row = $model_name::withTrashed()->findOrFail($id);
            $row->restore();
        }
        return redirect('admin/' . $this->route_params . $query_string)->with('message', 'بازیابی ' . $this->title . ' ها با موفقیت انجام شد.');
    }

    public function restore($id)
    {
        $model_name = '\\App\\' . $this->model;
        $query_string = property_exists($this,'queryString') ? '?' . $this->queryString : '';


        $row = $model_name::withTrashed()->findOrFail($id);
        $row->restore();
        return redirect('admin/' . $this->route_params .$query_string)->with('message', " بازیابی $this->title با موفقیت انجام شد.");
    }
}
