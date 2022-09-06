@extends('layouts.Shop')

@section('content')
    <shopping-cart :cart_data="{{ json_encode($cartData) }}"></shopping-cart>
@endsection
