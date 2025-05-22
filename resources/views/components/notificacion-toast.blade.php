@if((session('mensaje') && session('tipo')) || session('success') || session('error') || session('warning') || session('info'))    @php
        $mensaje = session('mensaje') ?? session('success') ?? session('error') ?? session('warning') ?? session('info');
        $tipo = session('tipo') ?? 
                (session('success') ? 'success' : 
                (session('error') ? 'error' : 
                (session('warning') ? 'warning' : 
                (session('info') ? 'info' : 'info'))));
                
        $colores = [
            'success' => ['bg' => 'bg-success', 'text' => 'text-success', 'icono' => '✔'],
            'error' => ['bg' => 'bg-danger', 'text' => 'text-danger', 'icono' => '✖'],
            'warning' => ['bg' => 'bg-warning', 'text' => 'text-warning', 'icono' => '⚠'],
            'info' => ['bg' => 'bg-info', 'text' => 'text-info', 'icono' => 'ℹ'],
        ];
        $color = $colores[$tipo] ?? $colores['info'];
        
        // Limpiar sesión para evitar duplicaciones
        if (session('success')) session()->forget('success');
        if (session('error')) session()->forget('error');
        if (session('warning')) session()->forget('warning');
        if (session('info')) session()->forget('info');
    @endphp    <div class="position-fixed top-0 end-0 p-4" style="z-index: 1100; max-width: 350px;">
        <div id="app-toast-notification" class="shadow-lg rounded-pill bg-white border-0 fade-in px-3 py-2 d-flex align-items-center gap-3" style="min-height: 60px;">
            <div class="{{ $color['bg'] }} text-white rounded-pill d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                <strong>{{ $color['icono'] }}</strong>            </div>
            <div class="text-start">
                <div class="fw-bold {{ $color['text'] }} mb-1">{{ ucfirst($tipo) }}</div>
                <div class="text-muted small">{{ $mensaje }}</div>
            </div>
            <button type="button" class="btn-close ms-auto" aria-label="Cerrar" onclick="this.closest('.fade-in').remove()" style="font-size: 0.8rem;"></button>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeInToast 0.4s ease-out;
        }

        @keyframes fadeInToast {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endif
