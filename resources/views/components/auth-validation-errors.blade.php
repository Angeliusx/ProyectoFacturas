@props(['errors', 'context' => '', 'userName' => ''])

@if ($errors->any())
    <div {{ $attributes }}>
        <div class="text-danger">
            @if ($context === 'register')
                {{ __('¡Uy! Se te olvidó algo al registrar tu cuenta') }}
            @elseif ($context === 'add_demand')
                {{ __("$userName verifica los datos ingresados:") }}
            @elseif ($userName !== '')
                {{ __("$userName se te olvidó algo") }}
            @endif
        </div>

        <ul class="mt-2 text-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
