@extends('layouts.admin')
@section('link')
    <link href="{{asset('css/dropzone.css')}}" rel="stylesheet">
@endsection

@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت محصولات','url'=>url('admin/product')],
    ['title'=>'گالری تصاویر','url'=>url('admin/product/gallery/'.$product->id)],
    ]])
    <div class="panel">
        <div class="header">
            گالری تصاویر ( {{$product->title}} )

            {{--            @include('Include.item_table',['count'=>$trashed_product_count,'route'=>'admin/product','title'=>'محصول'])--}}
        </div>
        <div class="panel_content">
            @include('Include.alert')
            <form action="{{url('admin/product/gallery_upload/'.$product->id)}}" class="dropzone" id="upload_file">
                @csrf
                <input type="file" name="file" multiple="multiple" class="d-none">
            </form>
            <table class="table table-bordered" id="gallery_table">
                <thead>
                <tr>
                    <th>ردیف</th>
                    <th>تصویر</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $i=(isset($_GET['page']) ? ($_GET['page']-1)*10+1 : 1 );
                @endphp
                @foreach($productGalleries as $value)
                    <tr id="{{$value->id}}">
                        <td>{{$i}}</td>
                        <td>
                            <img src="{{asset('files/uploads/products/gallerys/'.$value->image_url)}}">
                        </td>
                        <td>
                              <span data-toggle="tooltip" data-placement="bottom" title="حذف تصویر"
                                    class="fa fa-remove"
                                    onclick="del_row('{{url('admin/product/gallery/'.$value->id)}}','{{Session::token()}}','آیا از حذف این تصویر مطمئن هستید ؟')"></span>
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
@section('script')
    <script src="{{asset('js/dropzone.min.js')}}" type="text/javascript"></script>
    <script>
        Dropzone.options.uploadFile = {
            acceptedFiles: ".png,.jpg,.jpeg",
            addRemoveLinks: true,
            init: function () {
                this.options.dictRemoveFile = 'حذف';
                this.options.dictInvalidFileType = 'امکان آپلود این فایل وجود ندارد.';
                this.on('success', function (file, response) {
                    if (response == 1) {
                        file.previewElement.classList.add('dz-success');
                    } else {
                        file.previewElement.classList.add('dz-error');
                        $(file.previewElement).find('.dz-error-message').text('خطا در آپلود فایل');
                    }

                });
                this.on('error', function (file, response) {
                    file.previewElement.classList.add('dz-error');
                    $(file.previewElement).find('.dz-error-message').text('خطا در آپلود فایل');
                });
            }
        };
        const $sortable = $("#gallery_table > tbody");
        $sortable.sortable({
            stop: function (event, ui) {
                $("#loading_box").show();
                const parameters = $sortable.sortable("toArray");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax('{{url('admin/product/gallery/change_image_position/'.$product->id)}}', {
                    type: 'POST',
                    data: 'parameters=' + parameters,
                    success: function (data) {
                        $("#loading_box").hide();
                    },
                });
            }

        });
    </script>

@endsection