@php
  $menus = Admin::getMenuVisible();
@endphp
  <div class="navbar-minimize-fixed" style="opacity: 1;">
    <button class="minimize-sidebar btn btn-link btn-just-icon">
        <i class="tim-icons icon-align-center visible-on-sidebar-regular text-muted"></i>
        <i class="tim-icons icon-bullet-list-67 visible-on-sidebar-mini text-muted"></i>
    </button>
  </div>
<div class="sidebar">
    <div class="sidebar-wrapper">
      <div class="logo">
          <a href="{{ bc_route_admin('admin.home') }}" class="simple-text logo-mini">{{ __('BC') }}</a>
          <a href="{{ bc_route_admin('admin.home') }}" class="simple-text logo-normal">{{ __('Black-Cart') }}</a>
      </div>
      <ul class="nav">
          @if (count($menus))
            @foreach ($menus[0] as $level0)
              @if (!empty($menus[$level0->id]))
                <li class="">
                  <a data-toggle="collapse" href="#{!! 'open-nav-'.$level0->id !!}">
                    <i class="tim-icons {{ $level0->icon }}"></i>
                    <p>
                      {!! bc_language_render($level0->title) !!}
                      <b class="caret"></b>
                    </p>
                  </a>
                  <div class="collapse" id="{!! 'open-nav-'.$level0->id !!}">
                    <ul class="nav">
                      @foreach ($menus[$level0->id] as $level1)
                          @if($level1->uri)
                            <li class="{{ \Admin::checkUrlIsChild(url()->current(), bc_url_render($level1->uri)) ? 'active' : '' }}">
                                <a href="{{ bc_url_render($level1->uri) }}">
                                    <span class="sidebar-mini-icon">{!! mb_substr(bc_language_render($level1->title),0,1,'UTF-8') !!}</span>
                                    <span class="sidebar-normal"> {!! bc_language_render($level1->title) !!}
                                </a>
                            </li>
                          @else
                            @if (!empty($menus[$level1->id]))
                                <li>
                                  <a data-toggle="collapse" aria-expanded="false" href="#{{ 'open-nav-'.$level1->id }}" class="collapsed">
                                    <span class="sidebar-mini-icon">{!! mb_substr(bc_language_render($level1->title),0,1,'UTF-8') !!}</span>
                                    <span class="sidebar-normal"> {!! bc_language_render($level1->title) !!}
                                      <b class="caret"></b>
                                    </span>
                                  </a>
                                  <div class="collapse" id="{!! 'open-nav-'.$level1->id !!}">
                                    <ul class="nav">
                                      @foreach ($menus[$level1->id] as $level2)
                                        @if($level2->uri)
                                          <li class="{{ \Admin::checkUrlIsChild(url()->current(), bc_url_render($level2->uri)) ? 'active' : '' }}">
                                            <a href="{{ bc_url_render($level2->uri) }}">
                                              <span class="sidebar-mini-icon">{{ mb_substr(bc_language_render($level2->title),0,1,'UTF-8') }}</span>
                                              <span class="sidebar-normal"> {!! bc_language_render($level2->title) !!} </span>
                                            </a>
                                          </li>
                                        @endif
                                        @if (!empty($menus[$level2->id]))
                                            <li class="">
                                              <a data-toggle="collapse" aria-expanded="false" href="#{{ 'open-nav-'.$level3->id }}" class="collapsed">
                                                <span class="sidebar-mini-icon">{{ mb_substr(bc_language_render($level3->title),0,1,'UTF-8') }}</span>
                                                <span class="sidebar-normal"> {!! bc_language_render($level3->title) !!}
                                                  <b class="caret"></b>
                                                </span>
                                              </a>
                                              <div class="collapse" id="{!! 'open-nav-'.$level3->id !!}">
                                                <ul class="nav">
                                                  @foreach ($menus[$level2->id] as $level3)
                                                      <li class="">
                                                        <a href="{{ bc_url_render($level3->uri) }}">
                                                          <span class="sidebar-mini-icon">{{ mb_substr(bc_language_render($level3->title),0,1,'UTF-8') }}</span>
                                                          <span class="sidebar-normal"> {!! bc_language_render($level3->title) !!} </span>
                                                        </a>
                                                      </li>
                                                  @endforeach
                                                </ul>
                                              </div>
                                            </li>
                                        @endif
                                      @endforeach
                                    </ul>
                                  </div>
                                </li>
                            @endif
                          @endif
                      @endforeach
                    </ul>
                  </div>
                </li>
              @endif
            @endforeach
          @endif
          {{-- @if (\Admin::user()->checkUrlAllowAccess(route('admin_order.index')))
            @include($templatePathAdmin.'Component.sidebar_bottom')
          @endif --}}
      </ul>
    </div>
</div>