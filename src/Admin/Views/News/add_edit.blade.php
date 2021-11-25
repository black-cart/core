@extends($templatePathAdmin.'Layout.main')

@section('main')
<!-- form start -->
<form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-3 align-content-center">
                <h4 class="card-title align-self-center mb-0">{{ $title_description }}</h4>
                <a href="{{ bc_route_admin('admin_cms_content.index') }}" class="btn btn-primary" title="List">
                    <i class="tim-icons icon-minimal-left"></i> 
                </a>
            </div>
        </div>
    </div>
    @php
        $descriptions = $news?$news->descriptions->keyBy('lang')->toArray():[];
    @endphp
    @foreach ($languages as $code => $language)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $language->name }} {!! bc_image_render($language->icon,'20px','20px', $language->name) !!}</h4>
                </div>
                <div class="card-body">
                    <div class="form-group {{ $errors->has('descriptions.'.$code.'.title') ? ' text-red' : '' }}">
                        <label for="{{ $code }}__title">{{ trans('Plugins/Cms/Content::Content.title') }}</label>
                        <input type="text" id="{{ $code }}__title" name="descriptions[{{ $code }}][title]"
                            value="{{ old()? old('descriptions.'.$code.'.title'):($descriptions[$code]['title']??'') }}"
                            class="form-control {{ $code.'__title' }}" placeholder="{{ trans('admin.max_c',['max'=>200]) }}" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'descriptions.'.$code.'.title'])
                    </div>

                    <div class="form-group {{ $errors->has('descriptions.'.$code.'.keyword') ? ' text-red' : '' }}">
                        <h5 class="mb-1" for="{{ $code }}__keyword">{{  trans('Plugins/Cms/Content::Content.keyword') }}</h5>
                        <input type="text" id="{{ $code }}__keyword"
                            name="descriptions[{{ $code }}][keyword]"
                            value="{!! old()?old('descriptions.'.$code.'.keyword'):($descriptions[$code]['keyword']??'') !!}"
                            class="form-control tagsinput {{ $code.'__keyword' }}" placeholder="{{ trans('admin.max_c',['max'=>200]) }}" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'descriptions.'.$code.'.keyword'])
                    </div>

                    <div class="form-group {{ $errors->has('descriptions.'.$code.'.description') ? ' text-red' : '' }}">
                        <label for="{{ $code }}__description">{{ trans('Plugins/Cms/Content::Content.description') }}</label>
                        <textarea id="{{ $code }}__description"
                                name="descriptions[{{ $code }}][description]"
                                class="form-control {{ $code.'__description' }}" placeholder="{{ trans('admin.max_c',['max'=>300]) }}">{{  old()?old('descriptions.'.$code.'.description'):($descriptions[$code]['description']??'')  }}
                        </textarea>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'descriptions.'.$code.'.description'])
                    </div>
                    <div class="form-group kind {{ $errors->has('descriptions.'.$code.'.content') ? ' text-red' : '' }}">
                        <label for="{{ $code }}__content">{{ trans('Plugins/Cms/Content::Content.content') }}</label>
                        <textarea id="{{ $code }}__content" class="editor"
                            name="descriptions[{{ $code }}][content]">
                            {!! old('descriptions.'.$code.'.content',($descriptions[$code]['content']??'')) !!}
                        </textarea>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'descriptions.'.$code.'.content'])
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="carrd-title">Thông tin Chung</h4>
            </div>
            <div class="card-body">

                {{-- LAS --}}
                <div class="form-group kind  {{ $errors->has('alias') ? ' text-red' : '' }}">
                    <label for="alias">{!! trans('Plugins/Cms/Content::Content.alias') !!}</label>
                    <input type="text"  id="alias" name="alias"
                        value="{!! old('alias',($news['alias']??'')) !!}" class="form-control alias" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'alias'])
                </div>
                {{-- //LAS --}}
                {{-- Content Image --}}
                    <div class="form-group kind  {{ $errors->has('image') ? ' text-red' : '' }}">
                        <label for="image" class="align-self-center">{{ trans('Plugins/Cms/Content::Content.image') }}</label>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="thumbnail lfm" data-input="image" data-preview="preview_image" data-type="cms-image">
                                    <div class="img_holder" id="preview_image" >
                                        <img src="{{ old('image',$news['image']??'') ? asset(old('image',$news['image']??'')) : asset('admin/black/img/image_placeholder.jpg') }}">
                                    </div>
                                    <input type="hidden" id="image" name="image" value="{!! old('image',($news['image']??'')) !!}" class="image"/>
                                </div>
                            </div>

                            @include($templatePathAdmin.'Component.feedback',['field'=>'image'])
                        </div>
                    </div>
                {{-- //Content Image --}}
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
                        <label for="sort">{{ trans('Plugins/Cms/Content::Content.sort') }}</label>
                        <input type="number" id="sort" name="sort" value="{{ old()?old('sort'):$news['sort']??0 }}" class="form-control sort" placeholder="" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'sort'])
                    </div>
                {{-- //sort --}}
                {{-- status --}}
                    <div class="form-group">
                        <p class="status">{{ trans('Plugins/Cms/Content::Content.status') }}</p>
                        <input type="checkbox" 
                        {{ old('status',(empty($news['status'])?0:1))?'checked':''}} name="status" class="bootstrap-switch" data-on-label="ON" data-off-label="OFF">
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
@push('scripts')
    @include($templatePathAdmin.'Component.ckeditor_js')
    <script type="text/javascript">
        $('textarea.editor').ckeditor(
            {
                filebrowserImageBrowseUrl: '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}?type=product',
                filebrowserImageUploadUrl: '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}/upload?type=product&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}?type=Files',
                filebrowserUploadUrl: '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}/upload?type=file&_token={{csrf_token()}}',
                filebrowserWindowWidth: '900',
                filebrowserWindowHeight: '500'
            }
        );
    </script>
@endpush