<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Soporte - TicketSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8fafc] font-sans text-slate-900">

    <nav class="bg-[#1e293b] text-white shadow-lg px-8 py-3">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <span class="text-xl font-bold tracking-tight uppercase border-l-4 border-cyan-400 pl-3">TicketSystem</span>
            </div>

            <div class="hidden md:flex space-x-8 items-center text-sm">
                <a href="index.php?action=dashboard" class="text-slate-400 hover:text-white transition">Inicio</a>
                <a href="index.php?action=nuevo-ticket" class="text-slate-400 hover:text-white transition">Nuevo Ticket</a>
                <a href="index.php?action=mis-tickets" class="text-slate-400 hover:text-white transition">Mis Tickets</a>
                <a href="index.php?action=panel-soporte" class="text-cyan-400 font-semibold border-b-2 border-cyan-400 pb-1">Panel Soporte</a>
                <a href="#" class="text-slate-400 hover:text-white transition">Ayuda</a>
            </div>

            <div class="flex items-center space-x-3 bg-slate-800 px-4 py-1.5 rounded-full border border-slate-700">
                <div class="text-right">
                    <p class="text-[10px] text-slate-500 uppercase font-bold leading-none">Sesión actual</p>
                    <p class="text-xs font-medium text-slate-200"><?php echo $_SESSION['nombre'] ?? 'Admin'; ?></p>
                </div>
                <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center border border-slate-500">
                    <span class="text-[10px] font-bold text-cyan-400">ADM</span>
                </div>
                <a href="index.php?action=logout" class="text-slate-400 hover:text-red-400 ml-2 transition text-[10px] font-bold uppercase">
                    Salir
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto mt-12 px-6 pb-20">
        
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Panel de Control IT</h1>
            <p class="text-slate-500 mt-2 text-lg">Gestión global de todas las incidencias del sistema.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Pendientes</p>
                <p class="text-3xl font-black text-slate-800">18 Casos</p>
            </div>
            
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Críticos</p>
                <p class="text-3xl font-black text-slate-800">5 Tickets</p>
            </div>
            
            <div class="bg-emerald-500 p-8 rounded-3xl shadow-lg shadow-emerald-200 text-white">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-widest mb-1">Resueltos Hoy</p>
                <p class="text-3xl font-black">12 Éxitos</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Usuario</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Problema</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Prioridad</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Estado</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-cyan-100 text-cyan-600 rounded-xl flex items-center justify-center font-bold text-xs">JD</div>
                                    <div class="flex flex-col">
                                        <span class="text-slate-800 font-bold text-sm leading-tight">Juan Delgado</span>
                                        <span class="text-[10px] text-slate-400">ID: #4421</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-slate-700 font-medium text-sm">Falla en red Aula 4</span>
                                    <span class="text-[10px] text-cyan-500 font-bold uppercase mt-1">Conectividad</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 rounded text-[10px] font-bold uppercase bg-red-50 text-red-600 border border-red-100">Urgente</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-200">Abierto</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-cyan-500 hover:text-white transition-all text-[10px] font-bold uppercase tracking-widest">
                                    Gestionar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>