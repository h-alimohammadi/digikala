<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function addAddress(Request $request)
    {
        return Address::addAddress($request);
    }

    public function removeAddress($id, Request $request)
    {
        $address = Address::where(['id' => $id, 'user_id' => $request->user()->id])->first();
        if ($address) {
            if ($address->delete()){
                return Address::with(['province', 'city'])->where('user_id', $request->user()->id)->orderBy('id', 'desc')->get();
            }else{
                return 'error';
            }
        } else {
            return 'error';
        }
    }
}
