{{-- Use bc_config with storeId, dont use bc_config_admin because will switch the store to the specified store Id
--}}
{{-- Use bc_config with storeId, dont use bc_config_admin because will switch the store to the specified store Id
--}}

<div class="card">

  <div class="card-body table-responsivep-0">
   <table class="table table-hover box-body text-wrap table-bordered">
     <thead>
       <tr>
         <th>{{ trans('customer.config_manager.field') }}</th>
         <th>{{ trans('customer.config_manager.value') }}</th>
         <th>{{ trans('customer.config_manager.reqired') }}</th>
       </tr>
     </thead>
     <tbody>
       @foreach ($customerConfigs as $key => $customerConfig)
         <tr>
           <td>{{ bc_language_render($customerConfig['detail']) }}</td>
           <td><input class="check-data-config" data-store="{{ $storeId }}" type="checkbox" name="{{ $customerConfig['key'] }}"  {{ $customerConfig['value']?"checked":"" }}></td>
           <td>
             @if (!empty($customerConfigsRequired[$key.'_required']))
             <input class="check-data-config" data-store="{{ $storeId }}" type="checkbox" name="{{ $customerConfigsRequired[$key.'_required']['key'] }}"  {{ $customerConfigsRequired[$key.'_required']['value']?"checked":"" }}>
             @endif
           </td>
         </tr>
       @endforeach
     </tbody>
   </table>
  </div>
</div>