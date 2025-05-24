// When the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for the tipo_aprendizaje field
    const aprendizajeBtn = document.querySelector('.guardar-campo[data-campo="aprendizaje"]');
    if (aprendizajeBtn) {
        aprendizajeBtn.addEventListener('click', function() {
            // Get the selected learning type
            const tipoAprendizaje = document.getElementById('tipo_aprendizaje').value;

            // Build the form data
            const formData = new FormData();
            formData.append('tipo_aprendizaje', tipoAprendizaje);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value);

            // Send the AJAX request
            fetch(document.getElementById('aprendizajeForm').getAttribute('action'), {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the view
                    document.querySelector('#vista-aprendizaje p').textContent = tipoAprendizaje || 'No registrado';
                    document.querySelector('#vista-aprendizaje').classList.remove('d-none');
                    document.querySelector('#edicion-aprendizaje').classList.add('d-none');
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado!',
                        text: 'Tu estilo de aprendizaje ha sido actualizado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Hubo un problema al actualizar tu estilo de aprendizaje.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al conectar con el servidor.'
                });
            });
        });
    }
});
