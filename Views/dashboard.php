<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TicketSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] font-sans text-slate-900">

    <?php include 'Views/partials/navbar.php'; ?>

    <main class="max-w-4xl mx-auto mt-12 px-6 pb-20">
        
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-slate-800 tracking-tight">Bienvenido al Campus IT</h1>
            <p class="text-slate-500 mt-2 text-lg">¿En qué podemos ayudarte hoy?</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8 mb-10 text-center">
            <div class="flex justify-center mb-6">
                <div class="bg-cyan-50 p-4 rounded-2xl text-cyan-500">
                    <i class="fas fa-search text-4xl"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Rastrear Incidencia</h2>
            <form action="index.php" method="GET" class="max-w-md mx-auto flex gap-2">
                <input type="hidden" name="action" value="mis-tickets">
                <div class="relative flex-grow">
                    <i class="fas fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="id_ticket" placeholder="ID del ticket (ej: TK-9920)" 
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-400 outline-none transition-all text-sm">
                </div>
                <button type="submit" class="bg-[#1e293b] text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg">
                    Buscar
                </button>
            </form>
        </div>

        <div class="grid gap-4">
            <div class="bg-white border border-slate-100 rounded-2xl p-6 flex items-start gap-5 shadow-sm">
                <div class="bg-cyan-50 text-cyan-500 p-3 rounded-xl">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Cómo crear un buen ticket</h3>
                    <p class="text-slate-500 text-sm mt-1">1. Ve a «Nuevo Ticket» en el menú superior.</p>
                    <p class="text-slate-500 text-sm">2. Explica claramente qué ocurre y en qué equipo o aula.</p>
                    <p class="text-slate-500 text-sm">3. Añade capturas de pantalla si es posible.</p>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-6 flex items-start gap-5 shadow-sm">
                <div class="bg-emerald-50 text-emerald-500 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Buenas prácticas del soporte IT</h3>
                    <p class="text-slate-500 text-sm mt-1">• Describe el problema paso a paso.</p>
                    <p class="text-slate-500 text-sm">• Indica si afecta solo a tu equipo o a toda el aula.</p>
                    <p class="text-slate-500 text-sm">• Revisa tu correo institucional para actualizaciones.</p>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-6 flex items-start gap-5 shadow-sm">
                <div class="bg-blue-50 text-blue-500 p-3 rounded-xl">
                    <i class="fas fa-location-arrow text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">¿Dónde sigo mis incidencias?</h3>
                    <p class="text-slate-500 text-sm mt-1">
                        Usa <span class="font-semibold text-blue-600">«Mis Tickets»</span> para ver el estado de tus solicitudes y <span class="font-semibold text-blue-600">«Ayuda»</span> para resolver dudas sobre el uso del sistema.
                    </p>
                </div>
            </div>
        </div>

    </main>

</body>
</html>