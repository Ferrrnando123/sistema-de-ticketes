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
        
        <div class="text-center mb-10 animate-fadeInUp">
            <h1 class="text-4xl font-black text-slate-800 tracking-tight">Bienvenido</h1>
            <p class="text-slate-500 mt-2 text-lg">¿En qué podemos ayudarte hoy?</p>
        </div>


        <div class="grid gap-4">
            <div class="bg-white border border-slate-100 rounded-2xl p-6 flex items-start gap-5 shadow-sm hover-lift animate-fadeInUp delay-1">
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

            <div class="bg-white border border-slate-100 rounded-2xl p-6 flex items-start gap-5 shadow-sm hover-lift animate-fadeInUp delay-2">
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

            <div class="bg-white border border-slate-100 rounded-2xl p-6 flex items-start gap-5 shadow-sm hover-lift animate-fadeInUp delay-3">
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