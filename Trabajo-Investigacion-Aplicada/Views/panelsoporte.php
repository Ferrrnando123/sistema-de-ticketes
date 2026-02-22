<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Soporte IT - Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f1f5f9] font-sans text-slate-900">

    <nav class="bg-[#1e293b] text-white shadow-lg px-8 py-3">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <span class="text-xl font-bold tracking-tight uppercase border-l-4 border-cyan-400 pl-3">TicketSystem</span>
            </div>

            <div class="hidden md:flex space-x-8 items-center text-sm">
                <a href="dashboard.php" class="text-slate-400 hover:text-white transition">Inicio</a>
                <a href="mis-tickets.php" class="text-slate-400 hover:text-white transition">Mis Tickets</a>
                <a href="#" class="text-cyan-400 font-semibold border-b-2 border-cyan-400 pb-1">Panel Soporte</a>
                <a href="#" class="text-slate-400 hover:text-white transition">Ayuda</a>
            </div>

            <div class="flex items-center space-x-3 bg-cyan-900/30 px-4 py-1.5 rounded-full border border-cyan-700/50">
                <div class="text-right">
                    <p class="text-[10px] text-cyan-400 uppercase font-black leading-none tracking-widest">Administrador</p>
                    <p class="text-xs font-medium text-slate-200">Unknown User</p>
                </div>
                <div class="w-8 h-8 bg-cyan-600 rounded-full flex items-center justify-center border border-cyan-400 shadow-sm">
                    <i class="fas fa-user-shield text-xs text-white"></i>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto mt-10 px-6 pb-20">
        
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Panel de Soporte IT</h1>
            <p class="text-slate-500 mt-2">Vista global de incidencias, priorización y gestión de tickets de usuario.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Total Reportes</p>
                <p class="text-3xl font-black text-slate-800 mt-1">124</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <p class="text-amber-500 text-xs font-bold uppercase tracking-widest">Pendientes</p>
                <p class="text-3xl font-black text-slate-800 mt-1">18</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <p class="text-red-500 text-xs font-bold uppercase tracking-widest">Urgentes</p>
                <p class="text-3xl font-black text-slate-800 mt-1">5</p>
            </div>
            <div class="bg-emerald-500 p-6 rounded-2xl shadow-lg shadow-emerald-200 border border-emerald-400">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-widest">Resueltos Hoy</p>
                <p class="text-3xl font-black text-white mt-1">12</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
                <h3 class="font-bold text-slate-700">Todos los tickets registrados</h3>
                <div class="flex gap-2">
                    <button class="bg-white border border-slate-200 px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                    <button class="bg-white border border-slate-200 px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        <i class="fas fa-download mr-2"></i> Exportar
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Usuario</th>
                            <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Asunto / Problema</th>
                            <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Prioridad</th>
                            <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Estado</th>
                            <th class="px-8 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center text-[10px] font-bold">JD</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 leading-none">Juan Delgado</p>
                                        <p class="text-[10px] text-slate-400 mt-1">ID: #USR-442</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-medium text-slate-700">Pantalla azul al iniciar sistema</p>
                                <p class="text-[10px] text-cyan-500 font-bold uppercase mt-1">Hardware / Equipos</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase bg-red-100 text-red-600 border border-red-200">Crítica</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-500 border border-slate-200">Abierto</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <button title="Asignar técnico" class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-cyan-500 hover:border-cyan-200 transition-all">
                                        <i class="fas fa-user-plus text-xs"></i>
                                    </button>
                                    <button title="Responder" class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-emerald-500 hover:border-emerald-200 transition-all">
                                        <i class="fas fa-reply text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center text-[10px] font-bold">AM</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 leading-none">Ana Martínez</p>
                                        <p class="text-[10px] text-slate-400 mt-1">ID: #USR-105</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-medium text-slate-700">Acceso denegado a carpeta compartida</p>
                                <p class="text-[10px] text-cyan-500 font-bold uppercase mt-1">Permisos / Seguridad</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase bg-blue-100 text-blue-600 border border-blue-200">Normal</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-200">En Curso</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-cyan-500 hover:border-cyan-200 transition-all">
                                        <i class="fas fa-user-plus text-xs"></i>
                                    </button>
                                    <button class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-emerald-500 hover:border-emerald-200 transition-all">
                                        <i class="fas fa-reply text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-center">
                <nav class="flex gap-1">
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-slate-200 text-xs text-slate-400">1</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-slate-200 text-xs text-slate-400">2</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-slate-200 text-xs text-slate-400">3</button>
                </nav>
            </div>
        </div>
    </main>

</body>
</html>