@extends('layouts.admin')
@section('link')
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}"/>
    <link rel="stylesheet" href="{{asset('elFinder/css/elfinder.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('elFinder/css/theme.css')}}"/>
@endsection
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت فایل ها','url'=>url('admin/filemanager')],
    ]])
    <div class="panel">
        <div id="elfinder">

        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/jquery-ui.js')}}" type="text/javascript"></script>
    <script src="{{asset('elFinder/js/elfinder.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('elFinder/js/i18n/elfinder.fa.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#elfinder").elfinder({
                height: '470px',
                cssAutoLoad: false,
                url: '{{url('elFinder/php/connector.minimal.php')}}',
                lang: 'fa',
            });
        });
    </script>
@endsection
