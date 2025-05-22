@extends('components.layout')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Prueba de Notificaciones en Tiempo Real</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Envía notificaciones de prueba para verificar el funcionamiento de las notificaciones en tiempo real.</p>
                    
                    <form id="form-notificacion">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="Notificación de prueba">
                        </div>
                        
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <input type="text" class="form-control" id="mensaje" name="mensaje" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select class="form-select" id="tipo" name="tipo">
                                <option value="info">Información</option>
                                <option value="success">Éxito</option>
                                <option value="error">Error</option>
                                <option value="warning">Advertencia</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="icono" class="form-label">Icono (opcional)</label>
                            <input type="text" class="form-control" id="icono" name="icono" placeholder="fa-calendar-check">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Enviar notificación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-notificacion');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Obtener datos del formulario
        const formData = new FormData(form);
        
        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Enviar solicitud
        fetch('/pruebas/notificacion-instantanea', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                titulo: formData.get('titulo'),
                mensaje: formData.get('mensaje'),
                tipo: formData.get('tipo'),
                icono: formData.get('icono')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Limpiar el formulario
                form.reset();
                
                // Mostrar mensaje de éxito
                alert('Notificación enviada correctamente');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar la notificación');
        });
    });
});
</script>
@endsection
