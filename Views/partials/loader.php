<?php
// Views/partials/loader.php
// aqui hemos quitado el filtro de sesion para que cargue siempre
?>
<style>
    #loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #f8fafc;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.4s ease, visibility 0.4s ease;
    }

    .loader-content {
        text-align: center;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e2e8f0;
        border-top: 4px solid #22d3ee;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    .loader-text {
        font-family: 'Segoe UI', sans-serif;
        color: #1e293b;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: -0.025em;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loader-hidden {
        opacity: 0;
        visibility: hidden;
    }

    /* aqui hacemos el cambio para que el loader empiece oculto por defecto */
    .loader-initial-state {
        display: none !important;
    }
</style>

<!-- aqui realizamos la estructura visual del spinner -->
<div id="loader-wrapper" class="loader-initial-state">
    <div class="loader-content">
        <div class="spinner"></div>
        <p class="loader-text">TicketSystem</p>
    </div>
</div>

<!-- esta es la funcion para ocultar el loader despues de un tiempo y detectar refresco -->
<script>
    (function() {
        const loader = document.getElementById('loader-wrapper');
        // aqui hacemos el cambio para detectar si fue un refresco manual
        const navEntries = performance.getEntriesByType('navigation');
        const isReload = navEntries.length > 0 && navEntries[0].type === 'reload';

        if (isReload) {
            // Si es un refresco, mostramos el loader quitando la clase de ocultado inicial
            loader.classList.remove('loader-initial-state');
            
            window.addEventListener('load', function() {
                setTimeout(function() {
                    if (loader) {
                        loader.classList.add('loader-hidden');
                    }
                }, 800); // 800ms de duración
            });
        } else {
            // Si es navegacion normal, nos aseguramos que este oculto totalmente
            loader.style.display = 'none';
        }
    })();
</script>
