<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tickets - Sistema de Soporte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-md px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-indigo-600">TicketSystem</div>

            <div class="hidden md:flex space-x-8 items-center text-gray-600 font-medium">
                <a href="dashboard.php" class="hover:text-indigo-600 transition">Inicio</a>
                <a href="#" class="hover:text-indigo-600 transition">Nuevo Ticket</a>
                <a href="#" class="text-indigo-600 border-b-2 border-indigo-600 pb-1">Mis Tickets</a>
                <a href="#" class="hover:text-indigo-600 transition">Panel Soporte</a>
                <a href="#" class="hover:text-indigo-600 transition">Ayuda</a>
                
                <div class="w-10 h-10 bg-purple-700 text-white flex items-center justify-center rounded-full font-bold">
                    J
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto mt-10 px-6">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Mis Tickets</h1>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Crear Nuevo
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Asunto</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-indigo-600">#1024</td>
                        <td class="px-6 py-4 text-gray-800">Error al iniciar sesión en el portal</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">En Proceso</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">22 Feb 2026</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-gray-400 hover:text-indigo-600 mx-2"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-indigo-600">#1015</td>
                        <td class="px-6 py-4 text-gray-800">Solicitud de cambio de contraseña</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Resuelto</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">20 Feb 2026</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-gray-400 hover:text-indigo-600 mx-2"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>