<?php
// Views/partials/navbar.php
$current_action = $_GET['action'] ?? 'dashboard';
$isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
?>
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeInUp {
        animation: fadeInUp 0.5s ease forwards;
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    
    .hover-lift {
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
    }
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Selection Color */
    ::selection { background: #22d3ee50; color: #0891b2; }

    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .animate-pulse-red {
        animation: pulse-red 2s infinite;
    }

    body {
        background-image: 
            radial-gradient(at 0% 0%, hsla(199,100%,95%,1) 0, transparent 50%), 
            radial-gradient(at 50% 0%, hsla(187,100%,96%,1) 0, transparent 50%), 
            radial-gradient(at 100% 0%, hsla(199,100%,95%,1) 0, transparent 50%);
        background-attachment: fixed;
    }
</style>
<nav class="bg-[#1e293b] text-white shadow-lg px-8 py-3 sticky top-0 z-50 backdrop-blur-md bg-opacity-95">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center">
            <span class="text-xl font-bold tracking-tight uppercase border-l-4 border-cyan-400 pl-3">TicketSystem</span>
        </div>

        <div class="hidden md:flex space-x-8 items-center text-sm">
            <a href="index.php?action=dashboard" class="transition pb-1 <?= $current_action == 'dashboard' ? 'text-cyan-400 font-semibold border-b-2 border-cyan-400' : 'text-slate-400 hover:text-white' ?>">
                Inicio
            </a>
            
            <a href="index.php?action=nuevo-ticket" class="transition pb-1 flex items-center gap-2 <?= $current_action == 'nuevo-ticket' ? 'text-cyan-400 font-semibold border-b-2 border-cyan-400' : 'text-slate-400 hover:text-white' ?>">
                <i class="fas fa-plus-circle text-xs"></i>
                Nuevo Ticket
            </a>

            <a href="index.php?action=mis-tickets" class="transition pb-1 <?= $current_action == 'mis-tickets' ? 'text-cyan-400 font-semibold border-b-2 border-cyan-400' : 'text-slate-400 hover:text-white' ?>">
                Mis Tickets
            </a>

            <?php if ($isAdmin): ?>
                <a href="index.php?action=panel-soporte" class="transition pb-1 <?= $current_action == 'panel-soporte' ? 'text-cyan-400 font-semibold border-b-2 border-cyan-400' : 'text-slate-400 hover:text-white' ?>">
                    Panel Soporte
                </a>
            <?php endif; ?>
            
            <a href="index.php?action=ayuda" class="transition pb-1 <?= $current_action == 'ayuda' ? 'text-cyan-400 font-semibold border-b-2 border-cyan-400' : 'text-slate-400 hover:text-white' ?>">Ayuda</a>
        </div>

        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-3 bg-slate-800 px-4 py-1.5 rounded-full border border-slate-700">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] text-slate-500 uppercase font-bold leading-none">Sesión actual</p>
                    <p class="text-xs font-medium text-slate-200"><?= $_SESSION['nombre'] ?? 'Usuario' ?></p>
                </div>
                <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center border border-slate-500">
                    <i class="fas <?= $isAdmin ? 'fa-user-shield text-cyan-400' : 'fa-user-graduate text-slate-300' ?> text-xs"></i>
                </div>
            </div>
            <a href="index.php?action=logout" class="text-slate-400 hover:text-red-400 transition p-2">
                <i class="fas fa-power-off text-sm"></i>
            </a>
        </div>
    </div>
</nav>
