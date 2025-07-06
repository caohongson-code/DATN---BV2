<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title', 'Trang quản trị')</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Main CSS -->
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>

<body onload="time()" class="app sidebar-mini rtl">
  <!-- Header -->
  @include('admin.layouts.header')

  <!-- Sidebar -->
  @include('admin.layouts.sidebar')

  <!-- Main content -->
  <main class="app-content">
    @yield('content')
    <div class="text-center" style="font-size: 13px">
      <p><b>&copy; <script>document.write(new Date().getFullYear());</script> Phần mềm quản lý bán hàng | Dev By Trường</b></p>
    </div>
  </main>

  <!-- Scripts -->
  <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
  <script src="{{ asset('js/popper.min.js') }}"></script>
  <script src="https://unpkg.com/boxicons@latest/dist/boxicons.js"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  <script src="{{ asset('js/plugins/pace.min.js') }}"></script>
  <script src="{{ asset('js/plugins/chart.js') }}"></script>

  @yield('scripts')

  <script>
    function time() {
      var today = new Date();
      var weekday = ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];
      var day = weekday[today.getDay()];
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      m = checkTime(m); s = checkTime(s);
      var nowTime = h + " giờ " + m + " phút " + s + " giây";
      if (dd < 10) dd = '0' + dd;
      if (mm < 10) mm = '0' + mm;
      var formatted = `<span class="date"> ${day}, ${dd}/${mm}/${yyyy} - ${nowTime}</span>`;
      document.getElementById("clock").innerHTML = formatted;
      setTimeout(time, 1000);
    }
    function checkTime(i) {
      return (i < 10) ? "0" + i : i;
    }
  </script>
</body>
</html>
