@extends($templatePathAdmin.'Layout.main')

@section('main')
<form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main"  enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-3 align-content-center">
                <h4 class="card-title align-self-center mb-0">{{ $title }}</h4>
                <a href="{{ bc_route_admin('admin_banner.index') }}" class="btn btn-primary" title="List">
                    <i class="tim-icons icon-minimal-left"></i> 
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="carrd-title">Thông tin Chung</h4>
            </div>
            <div class="card-body">

                {{-- Title --}}
                <div class="form-group kind  {{ $errors->has('title') ? ' text-red' : '' }}">
                    <label for="title">{!! trans('banner.title') !!}</label>
                    <input type="title"  id="title" name="title"
                        value="{{ old()?old('title'):$banner['title']??'' }}" class="form-control title" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'title'])
                </div>
                {{-- //Title --}}

                {{-- Url --}}
                <div class="form-group kind  {{ $errors->has('url') ? ' text-red' : '' }}">
                    <label for="url">{!! trans('banner.url') !!}</label>
                    <input type="url"  id="url" name="url"
                        value="{{ old()?old('url'):$banner['url']??'' }}" class="form-control url" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'url'])
                </div>
                {{-- //Url --}}

                {{-- Type --}}
                @if (!empty($dataType))
                <div class="form-group kind  {{ $errors->has('type') ? ' text-red' : '' }}">
                    <label for="type">{{ trans('banner.type') }}</label>
                    <select class="form-control type selectpicker"
                        name="type">
                        @foreach ($dataType as $k => $v)
                            <option {{ (old('type', $banner['type']??'') ==  $k)?'selected':'' }} value="{{ $k }}">{{ $v }}
                            </option>
                        @endforeach
                    </select>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'type'])
                </div>
                @endif
                {{-- //Type --}}

                {{-- Target --}}
                <div class="form-group kind  {{ $errors->has('target') ? ' text-red' : '' }}">
                    <label for="target">{{ trans('banner.admin.select_target') }}</label>
                    <select class="form-control target selectpicker"
                        name="target">
                        @foreach ($arrTarget as $k => $v)
                            <option  value="{{ $k }}" {{ (old('target',$banner['target']??'') ==$k) ? 'selected':'' }}>{{ $v }}
                            </option>
                        @endforeach
                    </select>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'target'])
                </div>
                {{-- //Target --}}

                {{-- Banner Image --}}
                    <div class="form-group kind  {{ $errors->has('image') ? ' text-red' : '' }}">
                        <label for="image" class="align-self-center">{{ trans('banner.image') }}</label>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="thumbnail lfm" data-input="image" data-preview="preview_image" data-type="banner">
                                    <div class="img_holder" id="preview_image" >
                                        <img src="{{ old('image',$banner['image']??'') ? asset(old('image',$banner['image']??'')) : asset('admin/black/img/image_placeholder.jpg') }}">
                                    </div>
                                    <input type="hidden" id="image" name="image" value="{!! old('image',($banner['image']??'')) !!}"class="image"/>
                                </div>
                            </div>

                            @include($templatePathAdmin.'Component.feedback',['field'=>'image'])
                        </div>
                    </div>
                {{-- //Banner Image --}}
                {{-- Html --}}
                <div class="form-group {{ $errors->has('html') ? ' text-red' : '' }}">
                    <label for="html">{{ trans('email_template.html') }}</label>
                    <div class="col-md-12 p-0">
                        <textarea id="html" name="html" class="form-control d-none" rows="10">{{ old('html',$banner['html']??'') }}</textarea>
                    </div>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'html'])
                </div>
                {{-- //Html --}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hoàn Tất</h4>
            </div>
            <div class="card-body">
                {{-- sort --}}
                    <div class="form-group {{ $errors->has('sort') ? ' text-red' : '' }}">
                        <label for="sort">{{ trans('banner.sort') }}</label>
                        <input type="number" id="sort" name="sort" value="{{ old()?old('sort'):$banner['sort']??0 }}" class="form-control sort" placeholder="" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'sort'])
                    </div>
                {{-- //sort --}}

                {{-- status --}}
                    <div class="form-group">
                        <p class="status">{{ trans('banner.status') }}</p>
                        <input type="checkbox" 
                        {{ old('status',(empty($banner['status'])?0:1))?'checked':''}} name="status" class="bootstrap-switch" data-on-label="ON" data-off-label="OFF">
                    </div>
                {{-- //status --}}
            </div>
            <div class="card-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary submit">{{ trans('admin.submit') }}</button>
                    <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('css')
<style type="text/css">
    .CodeMirror-gutter {
        width: 30px!important;
    }
    .CodeMirror-sizer{
        margin-left: 31px!important;
    }
    .CodeMirror-linenumber{
        left: 0!important;
        width: 23px!important;
    }
</style>
{{-- <link rel="stylesheet" href="{{ asset('admin/plugin/mirror/doc/docs.css')}}"> --}}
<link rel="stylesheet" href="{{ asset('admin/plugin/mirror/lib/codemirror.css')}}">
@endpush

@push('scripts')
<script src="{{ asset('admin/plugin/mirror/lib/codemirror.js')}}"></script>{{-- 
<script src="{{ asset('admin/plugin/mirror/mode/javascript/javascript.js')}}"></script>
<script src="{{ asset('admin/plugin/mirror/mode/css/css.js')}}"></script>
<script src="{{ asset('admin/plugin/mirror/mode/htmlmixed/htmlmixed.js')}}"></script> --}}
<script>
    $('.submit').click(function(event) {
        $('#form-main').submit();
    });
    window.onload = function() {
      editor = CodeMirror(document.getElementById("html"), {
        mode: "text/html",
        value: document.documentElement.innerHTML
      });
    };
    var myModeSpec = {
    name: "htmlmixed",
    tags: {
        style: [["type", /^text\/(x-)?scss$/, "text/x-scss"],
                [null, null, "css"]],
        custom: [[null, null, "customMode"]]
    }
    }
    var editor = CodeMirror.fromTextArea(document.getElementById("html"), {
      lineNumbers: true,
      styleActiveLine: true,
      matchBrackets: true
    });
  </script>

@endpush
