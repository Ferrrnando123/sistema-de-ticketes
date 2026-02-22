<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Ticket - Control de Áreas UDB</title>
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

    <main class="max-w-3xl mx-auto mt-10 px-6 pb-20">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Reportar Incidencia en Campus</h1>
            <p class="text-slate-500 mt-2">Selecciona el área específica (Salones o Baños) para reportar el inconveniente.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
            <form action="index.php?action=guardar_ticket" method="POST" class="p-8 space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">¿Qué sucede?</label>
                    <input type="text" name="asunto" required placeholder="Ej: Aire acondicionado no enfría / Fuga de agua" 
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:bg-white outline-none transition-all">
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Área afectada</label>
                        <div class="relative">
                            <select name="ubicacion" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 outline-none appearance-none cursor-pointer">
                                <option value="" disabled selected>Selecciona el lugar</option>
                                <optgroup label="Salones de Clase">
                                    <option value="cisco">Laboratorio CISCO</option>
                                    <option value="tic1">Salón TIC-1</option>
                                    <option value="tic2">Salón TIC-2</option>
                                </optgroup>
                                <optgroup label="Servicios Sanitarios">
                                    <option value="bano_hombres">Baño de Hombres</option>
                                    <option value="bano_mujeres">Baño de Mujeres</option>
                                </optgroup>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Prioridad del reporte</label>
                        <div class="flex gap-2">
                            <label class="flex-1">
                                <input type="radio" name="prioridad" value="baja" class="hidden peer">
                                <div class="text-center py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 peer-checked:bg-emerald-500 peer-checked:text-white transition-all cursor-pointer text-xs font-bold uppercase">Baja</div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="prioridad" value="media" class="hidden peer" checked>
                                <div class="text-center py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 peer-checked:bg-blue-500 peer-checked:text-white transition-all cursor-pointer text-xs font-bold uppercase">Media</div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="prioridad" value="alta" class="hidden peer">
                                <div class="text-center py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 peer-checked:bg-red-500 peer-checked:text-white transition-all cursor-pointer text-xs font-bold uppercase">Alta</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Descripción del Problema</label>
                    <textarea name="descripcion" rows="4" required placeholder="Describe detalles importantes (Ej: El proyector del TIC-1 parpadea)..." 
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:bg-white outline-none transition-all"></textarea>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
                    <a href="index.php?action=dashboard" class="text-slate-400 hover:text-slate-600 font-bold text-sm px-4">Cancelar</a>
                    <button type="submit" class="bg-[#1e293b] text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Enviar Reporte
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 bg-blue-50/50 border border-blue-100 rounded-2xl p-6 flex items-start gap-4">
            <div class="bg-blue-500 text-white p-2 rounded-lg shadow-md">
                <i class="fas fa-university"></i>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                Este reporte será enviado al equipo técnico de la universidad. Asegúrate de indicar si el problema afecta el desarrollo de la clase en **CISCO, TIC-1 o TIC-2**.
            </p>
        </div>
    </main>
</body>
</html>