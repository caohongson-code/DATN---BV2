<header class="app-header">
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <ul class="app-nav">
      <li>
        <a class="app-nav__item" href="{{ route('admin.logout') }}"
           onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
          <i class='bx bx-log-out bx-rotate-180'></i>
        </a>

        <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
      </li>
    </ul>
</header>
