<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
  <div class="app-sidebar__user">
    <img class="app-sidebar__user-avatar" src="{{ asset('storage/' . $admin->avatar) }}" width="50px" alt="User Image">
    <div>
      <p class="app-sidebar__user-name"><b>{{ $admin->full_name }}</b></p>
      <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
    </div>
  </div>
  <hr>
  <ul class="app-menu">
    <li>
      <a class="app-menu__item {{ request()->is('admin/pos*') ? 'active' : '' }}" href="{{ url('admin/pos') }}">
        <i class='app-menu__icon bx bx-cart-alt'></i>
        <span class="app-menu__label">POS Bán Hàng</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{ request()->is('admin/accounts*') ? 'active' : '' }}" href="{{ url('/admin/accounts') }}">
        <i class='app-menu__icon bx bx-id-card'></i>
        <span class="app-menu__label">Quản lý nhân viên</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{ request()->is('admin/customers*') ? 'active' : '' }}" href="{{ url('/admin/customers') }}">
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

    <li>
      <a class="app-menu__item {{ request()->is('admin/orders*') ? 'active' : '' }}" href="{{ url('/admin/orders') }}">
        <i class='app-menu__icon bx bx-task'></i>
        <span class="app-menu__label">Quản lý đơn hàng</span>
      </a>
    </li>

    <li>
        <li>
            <a class="app-menu__item {{ request()->is('admin/roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
              <i class='app-menu__icon bx bx-shield-quarter'></i>
              <span class="app-menu__label">Quản lý chức vụ</span>
            </a>
          </li>


    <li>
        <a class="app-menu__item {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
            <i class='app-menu__icon bx bx-category'></i>
            <span class="app-menu__label">Quản lý danh mục</span>
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
                    <a class="dropdown-item {{ request()->is('admin/rams*') ? 'active' : '' }}" href="{{ url('/admin/rams') }}">
                        <i class="bx bx-chip me-1"></i> RAM
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->is('admin/storages*') ? 'active' : '' }}" href="{{ url('/admin/storages') }}">
                        <i class="bx bx-hdd me-1"></i> Bộ nhớ
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->is('admin/acolors*') ? 'active' : '' }}" href="{{ url('/admin/colors') }}">
                        <i class="bx bx-palette me-1"></i> Màu sắc
                    </a>
                </li>
            </ul>
        </li>


  <li>
      <a class="app-menu__item {{ request()->is('admin/salary*') ? 'active' : '' }}" href="{{ url('admin/salary') }}">
        <i class='app-menu__icon bx bx-dollar'></i>
        <span class="app-menu__label">Bảng kê lương</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{ request()->is('admin/reports*') ? 'active' : '' }}" href="{{ url('admin/reports') }}">
        <i class='app-menu__icon bx bx-pie-chart-alt-2'></i>
        <span class="app-menu__label">Báo cáo doanh thu</span>
      </a>
    </li>

    <li>
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
    </li>
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
