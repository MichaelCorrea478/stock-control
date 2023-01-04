
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link @if(Route::is('home')) active @endif">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('stocks.index') }}" class="nav-link @if(Route::is('stocks.*')) active @endif">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>Ações</p>
    </a>
</li>
