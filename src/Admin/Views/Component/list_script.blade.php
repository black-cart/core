<script type="text/javascript">
// PJAX
    $('.grid-refresh').click(function(e){
      e.preventDefault();
      $.pjax.reload({container:'#pjax-container'});
    });

    $(document).on('submit', '#button_search', function(event) {
      $.pjax.submit(event, '#pjax-container')
    })

    $(document).on('pjax:send', function() {
      $('#loading').show()
    })
    $(document).on('pjax:complete', function() {
      $('#loading').hide()
    })

    // tag a
    $(function(){
      if ($('#pjax-container').length) {
        $(document).pjax('a.page-link', '#pjax-container') 
      }
    })


    $(document).ready(function(){
    // does current browser support PJAX
      if ($.support.pjax) {
        $.pjax.defaults.timeout = 2000; // time in milliseconds
      }
    });

    @if (isset($buttonSort))
      $('#sort_order').change(function(event) {
        var url = $('#url-sort').data('urlsort')+'sort_order='+$(this).val();
        $.pjax({url: url, container: '#pjax-container'})
      });
    @endif
// END PJAX
// DELETE ITEM
function deleteItem(ids){
  swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true,
  }).fire({
    title: '{{ trans('admin.confirm_delete') }}',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: '{{ trans('admin.confirm_delete_yes') }}',
    confirmButtonColor: "#DD6B55",
    cancelButtonText: '{{ trans('admin.confirm_delete_no') }}',
    reverseButtons: true,
    preConfirm: function() {
        return new Promise(function(resolve) {
            $.ajax({
                method: 'post',
                url: '{{ $urlDeleteItem ?? '' }}',
                data: {
                  ids:ids,
                    _token: '{{ csrf_token() }}',
                },
                success: function (data) {
                    if(data.error == 1){
                      alertMsg('danger', data.msg, '{{ trans('admin.warning') }}');
                      $.pjax.reload('#pjax-container');
                      return;
                    }else{
                      $.pjax.reload('#pjax-container');
                      resolve(data);
                    }

                }
            });
        });
    }

  }).then((result) => {
    if (result.value) {
      alertMsg('success', '{{ trans('admin.confirm_delete_deleted_msg') }}', '{{ trans('admin.confirm_delete_deleted') }}');
    }
  })
}
// Select row

var selectedRows = function () {
    var selected = [];
    $('.grid-row-checkbox:checked').each(function(){
        selected.push($(this).data('id'));
    });

    return selected;
}
$(function () {
    //Enable check and uncheck all functionality
    $("body").on('click','.grid-select-all',function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $.each($(".box-body input[type='checkbox']"), function(index, val) {
            $(val).prop('checked',false);
        });
      } else {
        //Check all checkboxes
        $.each($(".box-body input[type='checkbox']"), function(index, val) {
            $(val).prop('checked',true);
        });
      }
      $(this).data("clicks", !clicks);
    });

});
// == end select row

$('.grid-trash').on('click', function() {
  var ids = selectedRows().join();
  deleteItem(ids);
});
// END DELETE ITEM
</script>