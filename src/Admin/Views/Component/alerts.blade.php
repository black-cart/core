{{-- Need add under script of sweetaleart2 --}}
<!--message-->
    @if(session()->has('success'))

    <script type="text/javascript">
      jQuery(document).ready(function($) {
        theme.showNotification('top','right','{{ session('success') }}',2);
      });
    </script>
    @endif

    @if(session()->has('error'))
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        theme.showNotification('top','right','{{ session('error') }}',4);
      });
    </script>
    @endif

    @if(session()->has('warning'))
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        theme.showNotification('top','right','{{ session('warning') }}',3);
      });
    </script>
    @endif
