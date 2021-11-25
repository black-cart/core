@extends($templatePathAdmin.'Layout.main')
@section('main')
  <div class="row">
    <div class="col-md-12">
      <div class="card" >
        <div class="card-header">
            <h4 class="card-title">{{$title}}</h4>
        </div>
        <div class="card-body" >
          <div class="form-check mt-3">
            <label class="form-check-label">
              <input class="form-check-input check-data-config-global" type="checkbox" name="url_seo_lang" {{ bc_config_global('url_seo_lang')?"checked":"" }}>
              <span class="form-check-sign"></span>
              {!! bc_language_render('seo.url_seo_lang') !!}
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')

<script type="text/javascript">

  $('input.check-data-config-global').change(function(e) {
    var isChecked = e.currentTarget.checked;
    isChecked = (isChecked == false)?0:1;
    var name = $(this).attr('name');
    $.ajax({
      url: '{{ $urlUpdateConfigGlobal }}',
      type: 'POST',
      dataType: 'JSON',
      data: {
          "_token": "{{ csrf_token() }}",
          "name": $(this).attr('name'),
          "value": isChecked
        },
    })
    .done(function(data) {
      if(data.error == 0){
        alertMsg('success', '{{ trans('admin.msg_change_success') }}');
      } else {
        alertMsg('danger', data.msg);
      }
    });

  });


</script>
@endpush