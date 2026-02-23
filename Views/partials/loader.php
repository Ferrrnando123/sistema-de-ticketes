<?php
// Views/partials/loader.php
// aqui realizamos la comprobacion de si se debe mostrar el loader
if (isset($_SESSION['mostrar_loading']) && $_SESSION['mostrar_loading'] === true):
    unset($_SESSION['mostrar_loading']);
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
</style>

<!-- aqui realizamos la estructura visual del spinner -->
<div id="loader-wrapper">
    <div class="loader-content">
        <div class="spinner"></div>
        <p class="loader-text">TicketSystem</p>
    </div>
</div>

// esta es la funcion para ocultar el loader despues de un tiempo
<script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('loader-wrapper');
        // El usuario pidió que dure milisegundos
        setTimeout(function() {
            if (loader) {
                loader.classList.add('loader-hidden');
            }
        }, 800); // 800ms de duración
    });
</script>
<?php endif; ?>
