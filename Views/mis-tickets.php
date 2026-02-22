<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tickets - Sistema de Soporte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] font-sans text-slate-900">

<nav class="bg-[#1e293b] text-white shadow-lg px-8 py-3">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <span class="text-xl font-bold tracking-tight uppercase border-l-4 border-cyan-400 pl-3">TicketSystem</span>
            </div>

            <div class="hidden md:flex space-x-8 items-center text-sm">
                <a href="index.php?action=dashboard" class="transition pb-1 <?php echo ($_GET['action'] == 'dashboard') ? 'text-cyan-400 font-semibold border-b-2 border-cyan-400' : 'text-slate-400 hover:text-white'; ?>">
                    Inicio
                </a>
                
                <a href="index.php?action=nuevo-ticket" class="transition pb-1 flex items-center gap-2 <?php echo ($_GET['action'] == 'nuevo-ticket') ? 'text-cyan-400 font-semibold border-b-2 border-cyan-400' : 'text-slate-400 hover:text-white'; ?>">
                    <i class="fas fa-plus-circle text-xs"></i>
                    Nuevo Ticket
                </a>

                <a href="index.php?action=mis-tickets" class="transition pb-1 <?php echo ($_GET['action'] == 'mis-tickets') ? 'text-cyan-400 font-semibold border-b-2 border-cyan-400' : 'text-slate-400 hover:text-white'; ?>">
                    Mis Tickets
                </a>
                
                <a href="#" class="text-slate-400 hover:text-white transition pb-1">Ayuda</a>
            </div>

            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-3 bg-slate-800 px-4 py-1.5 rounded-full border border-slate-700">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] text-slate-500 uppercase font-bold leading-none">Sesión actual</p>
                        <p class="text-xs font-medium text-slate-200"><?php echo $_SESSION['nombre'] ?? 'Usuario'; ?></p>
                    </div>
                    <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center border border-slate-500">
                        <i class="fas fa-user-secret text-xs text-slate-300"></i>
                    </div>
                </div>
                <a href="index.php?action=logout" class="text-slate-400 hover:text-red-400 transition p-2">
                    <i class="fas fa-power-off text-sm"></i>
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto mt-12 px-6 pb-20">
        
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Historial de Tickets</h1>
            <p class="text-slate-500 mt-2 text-lg">Consulta el seguimiento y las resoluciones de tus casos.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Referencia</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Asunto</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Prioridad</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Estado</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Actualizado</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                            <td class="px-8 py-6">
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-md text-xs font-bold border border-slate-200">#TK-9920</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-bold group-hover:text-cyan-600 transition-colors">Error en carga de archivos adjuntos</span>
                                    <span class="text-xs text-slate-400 mt-1 flex items-center gap-1"><i class="far fa-folder-open"></i> Soporte Técnico</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 rounded text-[10px] font-bold uppercase bg-red-50 text-red-600 border border-red-100">Alta</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span> En espera
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-500 font-medium">Hace 2 horas</td>
                            <td class="px-8 py-6 text-right">
                                <button class="h-9 w-9 inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-cyan-600 hover:border-cyan-200 hover:shadow-sm transition-all">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                            <td class="px-8 py-6">
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-md text-xs font-bold border border-slate-200">#TK-9845</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-bold group-hover:text-cyan-600 transition-colors">Solicitud de licencia Office 365</span>
                                    <span class="text-xs text-slate-400 mt-1 flex items-center gap-1"><i class="far fa-folder-open"></i> IT / Software</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 rounded text-[10px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">Media</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Finalizado
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-500 font-medium">20 Feb 2026</td>
                            <td class="px-8 py-6 text-right">
                                <button class="h-9 w-9 inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-cyan-600 hover:border-cyan-200 hover:shadow-sm transition-all">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-2xl shadow-sm">
            <div class="flex items-start space-x-4">
                <div class="text-blue-500 mt-1">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <div>
                    <h4 class="text-blue-800 font-bold text-sm uppercase tracking-wider mb-1">¿Necesitas actualizar información?</h4>
                    <p class="text-blue-700 text-sm leading-relaxed">
                        Si necesitas añadir detalles a un ticket existente, por favor <strong>responde al correo de confirmación</strong> que recibiste o contacta directamente con nuestro equipo de soporte indicando el <strong>número de ticket</strong> correspondiente.
                    </p>
                </div>
            </div>
        </div>

    </main>

</body>
</html>