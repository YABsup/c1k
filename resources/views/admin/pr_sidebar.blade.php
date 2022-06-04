<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- search form (Optional) -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </form>
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">Dashboard</li>
      <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-users"></i> <span>Главная</span></a></li>
      <li><a href="{{ route('admin.anketas.index') }}"><i class="fa fa-users"></i> <span>Заявки на партнерство</span></a></li>
      <li><a href="/admin/users_statistics"><i class="fa fa-users"></i> <span>Пользователи</span></a></li>

    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>
