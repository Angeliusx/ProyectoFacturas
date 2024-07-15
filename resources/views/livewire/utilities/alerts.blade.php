@if ($message = Session::get('success'))
    <div x-data x-init="callNotyf('{{ $message }}', 'success')">
    </div>
@endif

@if ($message = Session::get('error'))
    <div x-data x-init="callNotyf('{{ $message }}', 'error')">
    </div>
@endif

@if ($message = Session::get('warning'))
    <div x-data x-init="callNotyf('{{ $message }}', 'warning')">
    </div>
@endif

@if ($errors->any())
    <div x-data x-init="callNotyf('Paso un error!', 'error')">
    </div>
@endif

<script>
    var notyf = new Notyf({
        duration: 3000,
        position: {
            x: 'right',
            y: 'top',
        },
        types: [{
            type: 'success',
            background: '#00b894',
            icon: {
                className: 'fas fa-check-circle',
                tagName: 'i',
                color: '#fff'
            },
            dismissible: true
        }, {
            type: 'error',
            background: '#ff7675',
            icon: {
                className: 'fas fa-exclamation-circle',
                tagName: 'i',
                color: '#fff'
            },
            dismissible: true
        }, {
            type: 'warning',
            background: '#fdcb6e',
            icon: {
                className: 'fas fa-exclamation-triangle',
                tagName: 'i',
                color: '#fff'
            },
            dismissible: true
        }, {
            type: 'info',
            background: '#74b9ff',
            icon: {
                className: 'fas fa-info-circle',
                tagName: 'i',
                color: '#fff'
            },
            dismissible: true
        }]
    });

    var callNotyf = function(message, type) {
        notyf.dismissAll();
        notyf[type](message);
    };
</script>
