@if (Auth::guard('admin')->check() == true)
<li class="menu-title">Menu</li>
<li>
    <a href="{{ url('admin/') }}" class="waves-effect">
        <i class='fa fa-home'></i>
        <span>Dasbor</span>
    </a>
</li>
<li>
    <a href="{{ url('admin/admin/list') }}" class="waves-effect">
        <i class='fa fa-user-secret'></i>
        <span>Admin</span>
    </a>
</li>
<li>
    <a href="{{ url('admin/user/list') }}" class="waves-effect">
        <i class='fa fa-users'></i>
        <span>Pengguna</span>
    </a>
</li>
<li class="">
    <a href="{{ url('admin/permission/list') }}" class="waves-effect">
        <i class='fa fa-book'></i>
        <span>Riwayat Izin</span>
    </a>
</li>
<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect"><i class="fa fa-file-alt"></i> <span>Log</span></a>
    <ul class="sub-menu" aria-expanded="true">
        <li><a href="{{ url('admin/log/user_register') }}">Pendaftaran</a></li>
        <li><a href="{{ url('admin/log/user_login') }}">Pengguna Masuk</a></li>
        <li><a href="{{ url('admin/log/admin_login') }}">Admin Masuk</a></li>
    </ul>
</li>
<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect"><i class="fa fa-cogs"></i> <span>Pengaturan</span></a>
    <ul class="sub-menu" aria-expanded="true">
        <li><a href="{{ url('admin/settings/website_page/list') }}">Halaman</a></li>
        <li><a href="{{ url('admin/settings/website_configs') }}">Konfigurasi Website</a></li>
    </ul>
</li>
@endif