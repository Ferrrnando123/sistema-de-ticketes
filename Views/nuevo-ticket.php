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


    <?php include 'Views/partials/navbar.php'; ?>

    <main class="max-w-3xl mx-auto mt-10 px-6 pb-20">
        <div class="mb-8 animate-fadeInUp">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Reportar Incidencia en Campus</h1>
            <p class="text-slate-500 mt-2">Selecciona el área específica (Salones o Sanitarios) para reportar el inconveniente.</p>

            <?php if(isset($_GET['success'])): ?>
                <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 animate-bounce">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm font-bold">¡Incidencia reportada con éxito! La revisaremos pronto.</span>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
                <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="text-sm font-bold">Error al enviar el reporte. Por favor intenta de nuevo.</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden animate-fadeInUp delay-1">
            <form id="ticketForm" action="index.php?action=guardar_ticket" method="POST" class="p-8 space-y-6">
                
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
                                    <option value="sanitario_hombres">Sanitario de Hombres</option>
                                    <option value="sanitario_mujeres">Sanitario de Mujeres</option>
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
                    <button type="submit" id="submitBtn" class="bg-[#1e293b] text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg flex items-center gap-2">
                        <i class="fas fa-paper-plane" id="submitIcon"></i> <span id="btnText">Enviar Reporte</span>
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

    <script>
        document.getElementById('ticketForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const icon = document.getElementById('submitIcon');
            const text = document.getElementById('btnText');
            
            // Deshabilitar el botón para evitar múltiples clics
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            
            // Cambiar el icono y el texto para dar feedback visual
            icon.className = 'fas fa-spinner fa-spin';
            text.innerText = 'Enviando...';
        });
    </script>
</body>
</html>