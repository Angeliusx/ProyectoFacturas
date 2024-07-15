@php
$links = [
    [
        'href' => 'dashboard',
        'text' => 'Dashboard',
        'title' => false,
        'icon' => 'fas fa-fire',
        'is_multi' => false,
        'roles' => 'all',
    ],
    [
        'href' => [
            [
                'section_text' => 'Lista Demanda',
                'icon' => 'fas fa-list-check',
                'section_list' => [
                    ['href' => 'demandas-prima', 'text' => 'Demanda Prima', 'roles' => 'all' ],
                    ['href' => 'demandas-profuturo', 'text' => 'Demanda Profuturo', 'roles' => 'all'],
                ],
            ],
            [
                'section_text' => 'Añadir Demanda',
                'icon' => 'fas fa-square-plus',
                'section_list' => [
                    ['href' => 'add-demandas-prima', 'text' => 'Añadir Demanda Prima', 'roles' => 'all'],
                    ['href' => 'add-demandas-profuturo', 'text' => 'Añadir Demanda Profut.', 'roles' => 'all'],
                ],
            ],
        ],
        'title' => 'Demanda',
        'is_multi' => true,
        'roles' => 'all',
    ],
    [
        'href' => [
            [
                'section_text' => 'Lista Facturas',
                'icon' => 'fas fa-list-check',
                'section_list' => [
                    ['href' => 'facturas', 'text' => 'Facturas', 'roles' => 'all' ],
                    ['href' => 'facturas-retrasadas', 'text' => 'Facturas Retrasadas', 'roles' => 'all'],
                ],
            ],
            [
                'section_text' => 'Añadir Factura',
                'icon' => 'fas fa-square-plus',
                'section_list' => [
                    ['href' => 'add-facturas', 'text' => 'Crear Facturas', 'roles' => 'all'],
                ],
            ],
        ],
        'title' => 'Facturas',
        'is_multi' => true,
        'roles' => 'all',
    ],
    [
        'href' => 'empresas',
        'text' => 'Empresas/Correo',
        'title' => 'Empresas',
        'icon' => 'fas fa-building',
        'is_multi' => false,
        'roles' => 'user',
    ],
    
    [
        'href' => [
            [
                'section_text' => 'Panel del Admin',
                'section_list' => [['href' => 'view-user', 'text' => 'Usuarios'], ['href' => 'add-user', 'text' => 'Crear Usuarios']],
            ],
        ],
        'title' => 'Admin',
        'icon' => 'fas fa-users-rectangle',
        'is_multi' => true,
        'roles' => 'admin',
    ],
    [
        'href' => 'actividades',
        'text' => 'Actividades de Usuarios',
        'title' => false,
        'icon' => 'fas fa-chart-line',
        'is_multi' => false,
        'roles' => 'admin',
    ],
    [
        'href' => 'administraciones',
        'text' => 'Registro de Administracion',
        'title' => 'Administracion',
        'icon' => 'fas fa-user-tie',
        'is_multi' => false,
        'roles' => 'admin',
    ],
    

];
$navigation_links = json_decode(json_encode($links), false);
@endphp

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">{{ config('app.name', 'Laravel') }}</a>
        </div>
        @foreach ($navigation_links as $link)
            @if ($link->roles == 'admin' && auth()->user()->hasRole('admin'))
                <ul class="sidebar-menu">
                    @if (!$link->is_multi)   
                        @if($link->title)   
                            <li class="menu-header">{{ $link->title }}</li>  
                        @endif
                        <li class="{{ Request::routeIs($link->href) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route($link->href) }}"><i
                                class="{{ $link->icon ?? 'fas fa-chart-bar' }}"></i><span>{{ $link->text }}</span></a>
                        </li>   
                    @else
                        <li class="menu-header">{{ $link->title }}</li>
                        @foreach ($link->href as $section)
                            @php
                                $routes = collect($section->section_list)
                                    ->map(function ($child) {
                                        return Request::routeIs($child->href);
                                    })
                                    ->toArray();
                                $is_active = in_array(true, $routes);
                            @endphp

                            <li class="dropdown {{ $is_active ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="{{ $section->icon ?? 'fas fa-chart-bar' }}"></i> <span>{{ $section->section_text }}</span></a>
                                <ul class="dropdown-menu">
                                    @foreach ($section->section_list as $child)
                                        <li class="{{ Request::routeIs($child->href) ? 'active' : '' }}"><a
                                                class="nav-link"
                                                href="{{ route($child->href) }}">{{ $child->text }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    @endif
                </ul>
            @elseif ($link->roles == 'all' || $link->roles == 'user')
            <ul class="sidebar-menu">
                    @if (!$link->is_multi)   
                        @if($link->title)   
                            <li class="menu-header">{{ $link->title }}</li>  
                        @endif
                        <li class="{{ Request::routeIs($link->href) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route($link->href) }}"><i
                                class="{{ $link->icon ?? 'fas fa-chart-bar' }}"></i><span>{{ $link->text }}</span></a>
                        </li>   
                    @else
                        <li class="menu-header">{{ $link->title }}</li>
                        @foreach ($link->href as $section)
                            @php
                                $routes = collect($section->section_list)
                                    ->map(function ($child) {
                                        return Request::routeIs($child->href);
                                    })
                                    ->toArray();
                                $is_active = in_array(true, $routes);
                            @endphp

                            <li class="dropdown {{ $is_active ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="{{ $section->icon ?? 'fas fa-chart-bar' }}"></i> <span>{{ $section->section_text }}</span></a>
                                <ul class="dropdown-menu">
                                    @foreach ($section->section_list as $child)
                                        <li class="{{ Request::routeIs($child->href) ? 'active' : '' }}"><a
                                                class="nav-link"
                                                href="{{ route($child->href) }}">{{ $child->text }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    @endif
                </ul>
            @endif
        @endforeach
    </aside>
</div>