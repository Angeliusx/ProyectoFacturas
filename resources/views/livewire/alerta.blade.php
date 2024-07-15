
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>


<li class="dropdown"><a href="#" data-toggle="dropdown"
        class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
        @if(count($demandas) > 0)
            <span class="badge badge-danger">{{ count($demandas) }}</span>
        @else
            <i class="far fa-bell"></i>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-title">Lista de Demandas Prima</div>
        <div class="dropdown-divider"></div>

        @if(count($demandas) > 0)
            @foreach ($demandas as $demandap)
                <a class="dropdown-item copy-demand" data-clipboard-text="{{ $demandap->NR_DEMANDA }}">
                       {{ $demandap->NR_DEMANDA }}
                </a>
            @endforeach
        @else
            <p class="text-muted text-center">No hay nada</p>
        @endif
    </div>
</li>

<!-- Agrega el siguiente script JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializa Clipboard.js
        new ClipboardJS('.copy-demand', {
            text: function (trigger) {
                return trigger.getAttribute('data-clipboard-text');
            }
        }).on('success', function () {
        });
    });
</script>
