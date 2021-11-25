{{-- image file manager --}}
<script type="text/javascript">
(function( $ ){

      $.fn.filemanager = function(type, options) {
        type = type || 'other';

        this.on('click', function(e) {
          type = $(this).data('type') || type;//sc
          var route_prefix = (options && options.prefix) ? options.prefix : '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}';
          var target_input = $('#' + $(this).data('input'));
          var target_preview = $('#' + $(this).data('preview'));
          var images = [];
          var images_html = '';
          var hostname = window.location.protocol + "//" + window.location.host;
          window.open(route_prefix + '?type=' + type, '{{ trans('admin.file_manager') }}', 'width=900,height=600');
          if (target_input.attr('name') == 'sub_image') {
            $(this).removeClass('lfm');
            $(this).prev('button').addClass('lfm').removeClass('d-none');
          }
          window.SetUrl = function (items) {
            if (target_input.attr('name') == 'image') {
              items.length = 1;
            }
            var file_path = items.map(function (item) {
                return item.url;
            }).join(','); 

            // set the value of the desired input to image url
            target_input.val('').val(file_path).trigger('change');

            // clear previous preview
            target_preview.html('');

            // set or change the preview image src
            if (items.length > 1) {
              var width = 100 / items.length;
              var padd = '2px';
            }
            items.forEach(function (item) {
              images.push(hostname+item.thumb_url);
              images_html += '<img src="'+item.thumb_url+'" style="width:'+width+'%;padding:'+padd+';">';
            });
            if(items.length > 5){
              target_preview.imagesGrid({
                  images: images
              });
            }else{
              target_preview.html(images_html);
            }
            // trigger change event
            target_preview.trigger('change');
          };
          return false;
        });
      }

    })(jQuery);

    $('.lfm').filemanager();
</script>
{{-- //image file manager --}}


<script type="text/javascript">
  function format_number(n) {
      return n.toFixed(0).replace(/./g, function(c, i, a) {
          return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
      });
  }

// active tree menu
$('.nav-treeview > li.active').parents('.has-treeview').addClass('active menu-open');
// ==end active tree menu

</script>

<script>
    function LA() {}
    LA.token = "{{ csrf_token() }}";

    function alertJs(type = 'error', msg = '') {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
      Toast.fire({
        type: type,
        title: msg
      })
    }
    
    function alertMsg(type = 'danger', msg = '', note = '') {
      theme.showNotification('top','right',msg,type);
    }

    function alertConfirm(type = 'warning', msg = '') {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
      Toast.fire({
        type: type,
        title: msg
      })
    }
    $('[data-toggle="tooltip"]').tooltip();
    // $('.select2').select2();
</script>