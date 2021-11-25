@extends($templatePathAdmin.'Layout.main')

@section('main')
<div class="row">
  {{-- top --}}
  <div class="col-md-12">
    <div class="card" >
      <div class="card-header">
        <div class="tools float-right">
            <div class="dropdown">
                <button type="button" class="btn btn-default dropdown-toggle btn-link btn-icon" data-toggle="dropdown">
                    <i class="tim-icons icon-settings-gear-63"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                    <a class="dropdown-item" id="button_create_new" href="{{bc_route_admin('admin_email_template.create')}}">{{trans('admin.add_new')}}</a>
                    @if (!empty($buttonRefresh))
                        <a class="dropdown-item grid-refresh" href="#">
                            {{ trans('admin.refresh') }}
                        </a>
                    @endif
                    @if (!empty($removeList))
                        <a class="dropdown-item text-danger grid-trash" href="#">{{ trans('admin.delete') }}</a>
                    @endif
                </div>
            </div>
        </div>
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0" id="pjax-container">
        @php
            $urlSort = $urlSort ?? '';
        @endphp
        <div id="url-sort" data-urlsort="{!! strpos($urlSort, "?")?$urlSort."&":$urlSort."?" !!}"  style="display: none;"></div>
        <div class="table-responsive ps">
          <table class="table box-body">
            <thead>
              <tr>
                @if (!empty($removeList))
                <th class="text-center">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input grid-row-checkbox grid-select-all" type="checkbox">
                      <span class="form-check-sign"></span>
                    </label>
                  </div>
                </th>
                @endif
                  <th class="text-center">#</th>
                  <th>{{trans('email_template.name')}}</th>
                  <th>{{trans('email_template.group')}}</th>
                  <th>{{trans('email_template.status')}}</th>
                  <th>{{trans('email_template.admin.action')}}</th>
               </tr>
            </thead>
            <tbody>
              @foreach ($dataET as $keyRow => $row)
              <tr>
                  @if (!empty($removeList))
                  <td class="text-center">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input grid-row-checkbox" type="checkbox" data-id="{{ $row['id']??'' }}">
                        <span class="form-check-sign"></span>
                      </label>
                    </div>
                  </td>
                  @endif
                  <td class="text-center">{{$row['id']}}</td>
                  <td>{{$row['name'] ?? 'N/A'}}</td>
                  <td>{{$row['group'] ?? 'N/A'}}</td>
                  <td>{!! $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>' !!}</td>
                  <td>
                    @include($templatePathAdmin.'Component.action_list',['url_edit'=> bc_route_admin('admin_email_template.edit',['id'=>$row['id']]),'id'=>$row['id']])
                  </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <div class="block-pagination clearfix m-10">
          <div class="ml-3 float-left">
            {!! $resultItems??'' !!}
          </div>
          <div class="pagination pagination-sm mr-3 float-right">
            {!! $pagination??'' !!}
          </div>
        </div>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>
@endsection


@push('css')
<style type="text/css">
  .btn.dropdown-toggle[data-toggle=dropdown]{
    margin-bottom: 0px;
  }
  .pagination .page-item .page-link{
    line-height: 25px;
  }
</style>
{!! $css ?? '' !!}
@endpush

@push('scripts')
{{-- //Pjax --}}
<script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>
{{-- //End pjax --}}
{!! $js ?? '' !!}
@endpush
