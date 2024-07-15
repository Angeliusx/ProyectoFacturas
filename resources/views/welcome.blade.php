
<x-guest-layout>
    <div class="main-wrapper container">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <a href="{{ route('welcome') }}" class="navbar-brand sidebar-gone-hide">Sistema Procesal</a>
            <div class="nav-collapse">
                <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
        </nav>

        <nav class="navbar navbar-secondary navbar-expand-lg">
            <div class="container">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="fas fa-sign-in-alt"></i><span>Login</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Pagina de Inicio</h1>
                </div>

                <div class="section-body">
                    <!-- <h2 class="section-title">Example Stisla Laravel Breeze</h2>
                    <p class="section-lead">This page is brief explanation for Stisla Laravel Breeze</p> -->
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ config('app.name') }}</h4>
                        </div>
                        <div class="card-body">
                            <p>
                                Prueba de Pagina de inicio: Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium deleniti sit iste, cum enim maxime 
                                necessitatibus aliquid vel quo odio? Eligendi assumenda modi laborum totam atque tempora voluptatibus magnam sint.
                            </p>
                            <p>
                                Aqui no hay nada interesante
                            </p>
                            <p>Hola soy Angello :D</p>
                        </div>
                        
                        <div class="card-footer">
                            <small>Hoy es: {{ now() }}</small>
                        </div>
                    </div>

                </div>

                

                
            </section>
        </div>
        
        <footer class="main-footer">
            <div class="footer-left">
                <small class="text-muted">Equipo Procesal Fernandez ©2023</small>
            </div>
        </footer>
    </div>
</x-guest-layout>
