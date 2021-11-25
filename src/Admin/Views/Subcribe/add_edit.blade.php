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
                <a href="{{ bc_route_admin('admin_subscribe.index') }}" class="btn btn-primary" title="List">
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
                {{-- LAS --}}
                <div class="form-group kind  {{ $errors->has('email') ? ' text-red' : '' }}">
                    <label for="email">{!! trans('subscribe.email') !!}</label>
                    <input type="text"  id="email" name="email"
                        value="{!! old('email',($subscribe['email']??'')) !!}" class="form-control email" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'email'])
                </div>
                {{-- //LAS --}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hoàn Tất</h4>
            </div>
            <div class="card-body">
                {{-- status --}}
                    <div class="form-group">
                        <p class="status">{{ trans('subscribe.status') }}</p>
                        <input type="checkbox" 
                        {{ old('status',(empty($subscribe['status'])?0:1))?'checked':''}} name="status" class="bootstrap-switch" data-on-label="ON" data-off-label="OFF">
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
<script type="text/javascript">
    $('.submit').click(function(event) {
        $('#form-main').submit();
    });
</script>
@endpush