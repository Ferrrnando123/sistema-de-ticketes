<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Ayuda - TicketSystem</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2322d3ee'><path d='M2 8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 1 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 1 0 0-4V8z'/></svg>">
    <?php include 'Views/partials/loader.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] font-sans text-slate-900">

    <?php include 'Views/partials/navbar.php'; ?>

    <main class="max-w-5xl mx-auto mt-12 px-6 pb-20">
        
        <div class="text-center mb-12 animate-fadeInUp">
            <h1 class="text-4xl font-black text-slate-800 tracking-tight">Centro de Ayuda</h1>
            <p class="text-slate-500 mt-2 text-lg">Todo lo que necesitas saber para usar el sistema.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm hover-lift animate-fadeInUp delay-1">
                <div class="bg-cyan-50 text-cyan-500 w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-rocket text-xl"></i>
                </div>
                <h3 class="font-black text-slate-800 text-xl mb-4">¿Cómo empezar?</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="bg-cyan-100 text-cyan-700 text-[10px] font-bold px-2 py-0.5 rounded mt-1">1</span>
                        <p class="text-slate-600 text-sm">Dirígete a <span class="font-bold">"Nuevo Ticket"</span> en la barra superior.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="bg-cyan-100 text-cyan-700 text-[10px] font-bold px-2 py-0.5 rounded mt-1">2</span>
                        <p class="text-slate-600 text-sm">Selecciona el área (Laboratorio, Aula, Oficinas) y describe el fallo.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="bg-cyan-100 text-cyan-700 text-[10px] font-bold px-2 py-0.5 rounded mt-1">3</span>
                        <p class="text-slate-600 text-sm">Presiona enviar y recibirás un ID de seguimiento único.</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm hover-lift animate-fadeInUp delay-2">
                <div class="bg-red-50 text-red-500 w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-layer-group text-xl"></i>
                </div>
                <h3 class="font-black text-slate-800 text-xl mb-4">Niveles de Prioridad</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        <p class="text-sm"><span class="font-bold text-red-600">Alta:</span> Fallos que detienen las clases (Ej: No hay internet en todo el salón).</p>
                    </div>
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <p class="text-sm"><span class="font-bold text-blue-600">Media:</span> Equipos lentos o fallos parciales de software.</p>
                    </div>
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <p class="text-sm"><span class="font-bold text-emerald-600">Baja:</span> Consultas técnicas o solicitudes de nuevo software.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm hover-lift animate-fadeInUp delay-3 md:col-span-2">
                <div class="flex flex-col md:flex-row md:items-center gap-8">
                    <div class="bg-emerald-50 text-emerald-500 w-16 h-16 rounded-3xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 text-xl mb-2">Estado de tus incidencias</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Puedes revisar el avance de tus reportes en la sección <span class="font-bold text-cyan-600">"Mis Tickets"</span>. 
                            Si el personal de soporte deja un comentario o cambia el estado a <span class="italic text-emerald-600">"Resuelto"</span>, lo verás reflejado ahí instantáneamente.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-12 text-center p-8 bg-slate-800 rounded-3xl text-white shadow-xl animate-fadeInUp">
            <h4 class="font-bold text-lg mb-2">¿Problemas urgentes?</h4>
            <p class="text-slate-400 text-sm mb-4">Si el sistema no carga o necesitas atención inmediata:</p>
            <div class="flex flex-wrap justify-center gap-4">
                <span class="bg-slate-700 px-4 py-2 rounded-full text-xs border border-slate-600"><i class="fas fa-envelope mr-2"></i> soporte@udb.edu.sv</span>
                <span class="bg-slate-700 px-4 py-2 rounded-full text-xs border border-slate-600"><i class="fas fa-phone mr-2"></i> Ext. 2234</span>
            </div>
        </div>

    </main>

</body>
</html>