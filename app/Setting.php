<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Setting extends Model
{
    protected $table = 'setting';

    public function setData($request)
    {
        $update_data = [];
        foreach ($request as $key => $value) {
            if ($key != "_token") {
                if ($this->addTable($key, $value)) {
                    $update_data[$key] = $value;
                }
            }
        }

        return $update_data;
    }

    private function addTable($key, $value)
    {
        $row = DB::table('setting')->where('option_name', $key)->first();
        if ($row) {
            if (!empty($value)) {
                DB::table('setting')->where('option_name', $key)->update(['option_value' => $value]);
                return true;
            } else {
                DB::table('setting')->where('option_name', $key)->delete();
                return true;
            }
        } else {
            if (!empty($value)) {
                DB::table('setting')->insert(['option_name' => $key, 'option_value' => $value]);
                return true;
            }
        }
    }

    public function getData($keys)
    {
        $fetchData = [];
        $array = DB::table('setting')->whereIn('option_name', $keys)->pluck('option_value', 'option_name')->toArray();
        foreach ($keys as $option_name) {
            if (array_key_exists($option_name, $array)) {
                $fetchData[$option_name] = $array[$option_name];
            }
        }
        return $fetchData;
    }
}