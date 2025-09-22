<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
  <div class="app-sidebar__user">
<img class="app-sidebar__user-avatar"
     src="{{ $admin && $admin->avatar ? asset('storage/' . $admin->avatar) : asset('images/default-avatar.png') }}"
     width="50px"
     alt="User Image">

<div>
  <p class="app-sidebar__user-name">
    <b>{{ $admin->full_name ?? 'Admin' }}</b>
  </p>
  <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
</div>
  </div>
  <hr>
  <ul class="app-menu">
    <li>
      <a class="app-menu__item {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('admin/dashboard') }}">
        <i class='app-menu__icon bx bx-cart-alt'></i>
        <span class="app-menu__label">POS Bán Hàng</span>
      </a>
    </li>

    <li>
        <a class="app-menu__item
        {{ (request()->is('admin/accounts') || (request()->is('admin/accounts/*') && !request()->is('admin/accounts/show')))
            ? 'active' : '' }}"
        href="{{ route('accounts.index') }}">
        <i class='app-menu__icon bx bx-id-card'></i>
        <span class="app-menu__label">Quản lý nhân viên</span>
    </a>
    </li>

    <li>
      <a class="app-menu__item {{ request()->is('admin/customers*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
        <i class='app-menu__icon bx bx-user-voice'></i>
        <span class="app-menu__label">Quản lý khách hàng</span>
      </a>
    </li>
    <li>
      <a class="app-menu__item {{ request()->is('admin/products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
        <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
        <span class="app-menu__label">Quản lý sản phẩm</span>
      </a>
    </li>

   <li class="treeview {{ request()->is('admin/orders*') || request()->is('admin/return-requests*') ? 'is-expanded' : '' }}">
    <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon bx bx-task"></i>
        <span class="app-menu__label">Quản lý đơn hàng</span>
        <i class="treeview-indicator bx bx-chevron-right"></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <a class="treeview-item {{ request()->is('admin/orders*') ? 'active' : '' }}" 
               href="{{ url('/admin/orders') }}">
                <i class="icon bx bx-right-arrow-alt"></i> Đơn hàng
            </a>
        </li>
        <li>
            <a class="treeview-item {{ request()->is('admin/return-requests*') ? 'active' : '' }}" 
               href="{{ url('/admin/return-requests') }}">
                <i class="icon bx bx-right-arrow-alt"></i> Hoàn hàng
            </a>
        </li>
    </ul>
</li>


    <li>
        <li>
            <a class="app-menu__item {{ request()->is('admin/roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
              <i class='app-menu__icon bx bx-shield-quarter'></i>
              <span class="app-menu__label">Quản lý chức vụ</span>
            </a>
          </li>
          @php
          $userRoleId = auth()->user()->role->id ?? null;
      @endphp

      @if($userRoleId == 1)
      <li>
          <a class="app-menu__item {{ request()->is('admin/roles/assign') ? 'active' : '' }}"
          href="{{ route('roles.permissions.assign') }}">
              <i class='app-menu__icon bx bx-lock-alt'></i>
              <span class="app-menu__label">Phân quyền</span>
          </a>
      </li>
      @endif
          {{-- <li>
            <a class="app-menu__item {{ request()->is('admin/promotions*') ? 'active' : '' }}" href="{{ route('promotions.index') }}">
                <i class='app-menu__icon bx bx-purchase-tag'></i>
                <span class="app-menu__label">Quản lý voucher</span>
              </a>
            </li> --}}

    <li>
        <a class="app-menu__item {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
            <i class='app-menu__icon bx bx-category'></i>
            <span class="app-menu__label">Quản lý danh mục</span>
          </a>
        </li>

    <li>
        <a class="app-menu__item {{ request()->is('admin/news*') ? 'active' : '' }}" href="{{ route('news.index') }}">
            <i class='app-menu__icon bx bx-news'></i>
            <span class="app-menu__label">Quản lý tin tức</span>
          </a>
        </li>
          <li>
        <a class="app-menu__item {{ request()->is('admin/contact*') ? 'active' : '' }}" href="{{ route('admin.contact.index') }}">
            <i class='app-menu__icon bx bx-news'></i>
            <span class="app-menu__label">Quản lý liên hệ</span>
          </a>
        </li>
        <li class="dropdown app-menu__item-wrapper position-relative">
            <a class="app-menu__item dropdown-toggle {{ request()->is('admin/attributes*') ? 'active' : '' }}"
               href="#"
               id="attributeDropdown"
               role="button">
                <i class='app-menu__icon bx bx-package'></i>
                <span class="app-menu__label">Thuộc tính sản phẩm</span>
            </a>

            <ul class="dropdown-menu show-on-hover position-absolute w-100"
                aria-labelledby="attributeDropdown"
                style="top: 100%; left: 0; display: none;">
                <li>
                    <a class="dropdown-item {{ request()->is('admin/rams*') ? 'active' : '' }}" href="{{ route('rams.index') }}">
                        <i class="bx bx-chip me-1"></i> RAM
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->is('admin/storages*') ? 'active' : '' }}" href="{{ route('storages.index') }}">
                        <i class="bx bx-hdd me-1"></i> Bộ nhớ
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->is('admin/colors*') ? 'active' : '' }}" href="{{ route('colors.index') }}">
                        <i class="bx bx-palette me-1"></i> Màu sắc
                    </a>
                </li>
            </ul>
        </li>


  <li>
      <a class="app-menu__item {{ request()->is('admin/salary*') ? 'active' : '' }}" href="{{ route('home')}}">
        <i class='app-menu__icon bx 	bx bx-home'></i>
        <span class="app-menu__label">Trang khách hàng</span>
      </a>
    </li>

    <li>
        <a class="app-menu__item {{ request()->is('admin/accounts/show') ? 'active' : '' }}" href="{{ route('admin.profile') }}">
          <i class='app-menu__icon bx bx-user'></i>
          <span class="app-menu__label">Thông tin cá nhân</span>
        </a>
      </li>
      <li>
      <a class="app-menu__item {{ request()->is('admin/promotions*') ? 'active' : '' }}" href="{{ route('promotions.index') }}">
        <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
        <span class="app-menu__label">Quản lý khuyến mãi</span>
      </a>
    </li>
      <li>
      <a class="app-menu__item {{ request()->is('admin/promotions*') ? 'active' : '' }}" href="{{ route('comments.index') }}">
        <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
        <span class="app-menu__label">Quản lý bình luận</span>
      </a>
    </li>

    {{-- <li>
        <a class="app-menu__item {{ request()->is('admin/login*') ? 'active' : '' }}" href="{{ route('login') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
            <span class="app-menu__label">Đăng xuất</span>
        </a>

        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li> --}}


    {{-- <li>
      <a class="app-menu__item {{ request()->is('admin/calendar*') ? 'active' : '' }}" href="{{ url('admin/calendar') }}">
        <i class='app-menu__icon bx bx-calendar-check'></i>
        <span class="app-menu__label">Lịch công tác</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ url('admin/settings') }}">
        <i class='app-menu__icon bx bx-cog'></i>
        <span class="app-menu__label">Cài đặt hệ thống</span>
      </a>
    </li> --}}
  </ul>
</aside>
<style>
    /* Hiển thị dropdown khi rê chuột */
    .nav-item.dropdown:hover > .dropdown-menu.show-on-hover {
        display: block !important;
        z-index: 1000;
        background: white;
        min-width: 180px;
        border-radius: 4px;
        padding: 0.25rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    /* Hiển thị menu con khi hover */
.app-menu__item-wrapper:hover > .dropdown-menu.show-on-hover {
    display: block !important;
}

    </style>
