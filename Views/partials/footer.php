<?php
// Views/partials/footer.php
?>
<footer class="bg-[#1e293b] text-slate-400 py-12 px-8 mt-auto border-t border-slate-700/50">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-10 text-center md:text-left">
            <div>
                <div class="flex items-center justify-center md:justify-start gap-3 mb-6">
                    <div class="w-8 h-8 bg-cyan-500 rounded-lg flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <i class="fas fa-ticket text-white text-xs"></i>
                    </div>
                    <span class="text-white font-bold tracking-tight uppercase">TicketSystem</span>
                </div>
                <p class="text-sm leading-relaxed max-w-xs mx-auto md:mx-0">
                    Plataforma oficial de gestión de incidencias IT para el Campus UDB. Rapidez y transparencia en cada reporte.
                </p>
            </div>

            <div>
                <h4 class="text-white font-bold uppercase text-xs tracking-widest mb-6">Navegación</h4>
                <div class="flex flex-col gap-3 text-sm">
                    <a href="index.php?action=dashboard" class="hover:text-cyan-400 transition-colors">Inicio</a>
                    <a href="index.php?action=nuevo-ticket" class="hover:text-cyan-400 transition-colors">Nuevo Ticket</a>
                    <a href="index.php?action=ayuda" class="hover:text-cyan-400 transition-colors">Centro de Ayuda</a>
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold uppercase text-xs tracking-widest mb-6">Soporte Directo</h4>
                <div class="text-sm space-y-3">
                    <p class="flex items-center justify-center md:justify-start gap-2">
                        <i class="fas fa-envelope text-cyan-500 w-4"></i>
                        soporte@udb.edu.sv
                    </p>
                    <p class="flex items-center justify-center md:justify-start gap-2">
                        <i class="fas fa-phone text-cyan-500 w-4"></i>
                        +503 2234-5678 (Ext. 2234)
                    </p>
                    <p class="flex items-center justify-center md:justify-start gap-2 italic text-slate-500">
                        San Salvador, El Salvador.
                    </p>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-medium uppercase tracking-widest">
                &copy; <?= date('Y') ?> <span class="text-white">Universidad Don Bosco</span> - Campus IT
            </p>
            <div class="flex gap-6 text-slate-500">
                <a href="#" class="hover:text-white transition-colors"><i class="fab fa-facebook-f text-sm"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fab fa-twitter text-sm"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fab fa-instagram text-sm"></i></a>
            </div>
        </div>
    </div>
</footer>
