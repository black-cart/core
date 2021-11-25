@extends($templatePathAdmin.'Layout.main')

@section('main')
<!-- form start -->
<form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-3 align-content-center">
                <h4 class="card-title align-self-center mb-0">{{ $title }}</h4>
                <a href="{{ bc_route_admin('email_template.index') }}" class="btn btn-primary" title="List">
                    <i class="tim-icons icon-minimal-left"></i> 
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="carrd-title">Thông tin Chung</h4>
            </div>
            <div class="card-body">
                {{-- Name --}}
                <div class="form-group kind  {{ $errors->has('name') ? ' text-red' : '' }}">
                    <label for="name">{!! trans('email_template.name') !!}</label>
                    <input type="text"  id="name" name="name"
                        value="{!! old('name',($ET['name']??'')) !!}" class="form-control name" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'name'])
                </div>
                {{-- //Name --}}
                {{-- Group --}}
                <div class="form-group kind  {{ $errors->has('group') ? ' text-red' : '' }}">
                    <label for="group">{{ trans('email_template.group') }}</label>
                    <select class="form-control group selectpicker" name="group">
                        <option value="">{{ trans('email_template.group') }}</option>
                        @foreach ($arrayGroup as $k => $v)
                            <option value="{{ $k }}"
                                {{ (old('group', $ET['group']??'') ==$k) ? 'selected':'' }}>{{ $v }}
                            </option>
                        @endforeach
                    </select>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'group'])
                </div>
                {{-- //Group --}}
                <div class="form-group">
                      <label>{{ trans('email_template.admin.variable_support') }}</label>
                      <ul class="list-inline" id="list-variables">
                      </ul>
                </div>
                {{-- Html --}}
                <div class="form-group {{ $errors->has('text') ? ' text-red' : '' }}">
                    <label for="text">{{ trans('email_template.text') }}</label>
                    <div class="col-md-12 p-0">
                        <textarea id="text" name="text" class="form-control d-none" rows="10">{{ old('text',$ET['text']??'') }}</textarea>
                    </div>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'text'])
                </div>
                {{-- //Html --}}

                {{-- status --}}
                    <div class="form-group">
                        <p class="status">{{ trans('email_template.status') }}</p>
                        <input type="checkbox" 
                        {{ old('status',(empty($ET['status'])?0:1))?'checked':''}} name="status" class="bootstrap-switch" data-on-label="ON" data-off-label="OFF">
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
<link rel="stylesheet" href="{{ asset('admin/plugin/mirror/lib/codemirror.css')}}">
@endpush

@push('scripts')
<script src="{{ asset('admin/plugin/mirror/lib/codemirror.js')}}"></script>
<script src="{{ asset('admin/plugin/mirror/mode/javascript/javascript.js')}}"></script>
<script src="{{ asset('admin/plugin/mirror/mode/css/css.js')}}"></script>
<script src="{{ asset('admin/plugin/mirror/mode/htmlmixed/htmlmixed.js')}}"></script>
<script>
    $('.submit').click(function(event) {
        $('#form-main').submit();
    });
    window.onload = function() {
      editor = CodeMirror(document.getElementById("text"), {
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
    var editor = CodeMirror.fromTextArea(document.getElementById("text"), {
      lineNumbers: true,
      styleActiveLine: true,
      matchBrackets: true
    });
</script>
@if($ET)
<script type="text/javascript">
    $(document).ready(function(){
        var group = $("[name='group'] option:selected").val();
        loadListVariable(group);
    });
</script>
@endif
<script type="text/javascript">
    $("[name='group']").change(function(){
        var group = $("[name='group'] option:selected").val();
        loadListVariable(group);        
    });
    function loadListVariable(group){
    $.ajax({
        type: "get",
        data:{key:group},
        url: "{{route('admin_email_template.list_variable')}}",
        dataType: "json",
        beforeSend: function(){
                $('#loading').show();
            },        
        success: function (data) {
            html = '';
            $.each(data, function(i, item) {
                html +='<li  class="list-inline-item"><code>'+item+'</code></li>';
            });          
            $('#list-variables').html(html);
            $('#loading').hide();
        }
    })

    }
</script>
@endpush