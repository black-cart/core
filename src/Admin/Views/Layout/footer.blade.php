
<footer class="footer">
  @if (!bc_config_global('ADMIN_FOOTER_OFF'))
    <div class="container-fluid">
        <ul class="nav">
            <li class="nav-item">
                <a href="#" target="blank" class="nav-link">
                    {{ __('Version') }} : {{ config('black-cart.sub-version') }}
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="https://updivision.com" target="blank" class="nav-link">
                    {{ __('Updivision') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    {{ __('About Us') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    {{ __('Blog') }}
                </a>
            </li> --}}
        </ul>
        <div class="copyright">
            Copyright &copy; {{ now()->year }} {{ __('made with') }} <i class="tim-icons icon-heart-2"></i> {{ __('by') }}
            <a href="{{ config('black-cart.github') }}" target="_blank">{{ config('black-cart.auth') }}</a> &amp;
            <a href="https://updivision.com" target="_blank">{{ __('Updivision') }}</a> {{ __('for a better web') }}.
        </div>
    </div>
  @endif
</footer>
