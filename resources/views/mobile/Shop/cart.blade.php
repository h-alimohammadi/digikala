@extends('layouts.mobile')

@section('content')
    <mobile-shopping-cart :cart_data="{{ json_encode($cartData) }}"></mobile-shopping-cart>
@endsection