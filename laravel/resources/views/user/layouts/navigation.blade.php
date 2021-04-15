@if (Auth::check() == true)
<li class="menu-title">Menu</li>
<li class="@if (request()->path() == '/') mm-active @endif">
    <a href="{{ url('/') }}" class="waves-effect @if (request()->path() == '/') active @endif">
        <i class='fa fa-home'></i>
        <span>Dasbor</span>
    </a>
</li>
<li class="">
    <a href="{{ url('inout/list') }}" class="waves-effect">
        <i class='fa fa-book'></i>
        <span>Check In/Out</span>
    </a>
</li>
<li class="">
    <a href="{{ url('permission/list') }}" class="waves-effect">
        <i class='fa fa-book'></i>
        <span>Riwayat Izin</span>
    </a>
</li>
<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect"><i class="fa fa-file"></i> <span>Halaman</span></a>
    <ul class="sub-menu" aria-expanded="true">
        @foreach (\App\Models\WebsitePage::all() as $item)
            <li><a href="{{ url('page/site/'.$item->slug.'') }}">{{ $item->title }}</a></li>
        @endforeach
    </ul>
</li>
@else
<li class="has-submenu">
    <a href="{{ url('/') }}">
        <i class="menu-icon fa fa-home"></i>
        <span>Dasbor</span>
    </a>
</li>
<li class="has-submenu">
    <a href="{{ url('auth/login') }}"> 
        <i class="menu-icon fa fa-sign-in-alt"></i>
        <span>Masuk</span>
    </a>
</li>
@if (Route::has('user.register'))
<li class="has-submenu">
    <a href="{{ url('auth/register') }}"> 
        <i class="menu-icon fa fa-user-plus"></i>
        <span>Daftar</span>
    </a>
</li>
@endif
@if (Route::has('user.reset'))
<li class="has-submenu">
    <a href="{{ url('auth/reset') }}"> 
        <i class="menu-icon fa fa-key"></i>
        <span>Atur Ulang Kata Sandi</span>
    </a>
</li>
@endif
<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect"><i class="fa fa-file"></i> <span>Halaman</span></a>
    <ul class="sub-menu" aria-expanded="true">
        @foreach (\App\Models\WebsitePage::all() as $item)
            <li><a href="{{ url('page/site/'.$item->slug.'') }}">{{ $item->title }}</a></li>
        @endforeach
    </ul>
</li>
@endif