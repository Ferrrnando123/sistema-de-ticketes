<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tickets - Sistema de Soporte</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2322d3ee'><path d='M2 8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 1 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 1 0 0-4V8z'/></svg>">
    <!-- aqui realizamos la carga del loader condicional -->
    <?php include 'Views/partials/loader.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] font-sans text-slate-900">

    <!-- aqui realizamos la carga de la navegacion superior -->
    <?php include 'Views/partials/navbar.php'; ?>

    <main class="max-w-7xl mx-auto mt-12 px-6 pb-20">
        
        <!-- aqui realizamos el titulo y descripcion del historial -->
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Historial de Tickets</h1>
            <p class="text-slate-500 mt-2 text-lg">Consulta el seguimiento y las resoluciones de tus casos.</p>
        </div>

        <!-- aqui realizamos el listado de tickets en una tabla -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Referencia</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Asunto</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Prioridad</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Estado</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Fecha</th>
                            <!-- aqui hemos quitado la columna de detalles por peticion del usuario -->
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <!-- aqui realizamos la comprobacion de si hay tickets para mostrar -->
                        <?php if (empty($tickets)): ?>
                            <tr>
                                <td colspan="6" class="px-8 py-10 text-center text-slate-400">
                                    <i class="fas fa-folder-open text-3xl mb-3 block"></i>
                                    No has reportado incidencias aún.
                                </td>
                            </tr>
                        <?php else: ?>
                            <!-- aqui realizamos el recorrido de cada ticket encontrado -->
                            <?php foreach ($tickets as $t): ?>
                                <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                                    <td class="px-8 py-6">
                                        <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-md text-xs font-bold border border-slate-200">#TK-<?= $t['id'] ?></span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-slate-800 font-bold group-hover:text-cyan-600 transition-colors"><?= htmlspecialchars($t['asunto']) ?></span>
                                            <span class="text-xs text-slate-400 mt-1 flex items-center gap-1"><i class="fas fa-map-marker-alt"></i> <?= ucwords(str_replace('_', ' de ', $t['ubicacion'])) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <!-- aqui hacemos el cambio de color segun la prioridad del caso -->
                                        <?php 
                                            $pColor = ($t['prioridad'] == 'alta') ? 'red' : (($t['prioridad'] == 'media') ? 'blue' : 'emerald');
                                        ?>
                                        <span class="px-3 py-1 rounded text-[10px] font-bold uppercase bg-<?= $pColor ?>-50 text-<?= $pColor ?>-600 border border-<?= $pColor ?>-100"><?= $t['prioridad'] ?></span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <!-- aqui realizamos el cambio de etiquetas segun el estado actual -->
                                        <?php 
                                            $eColor = ($t['estado'] == 'resuelto') ? 'emerald' : (($t['estado'] == 'en_proceso') ? 'amber' : 'slate');
                                            $eLabel = ($t['estado'] == 'resuelto') ? 'Finalizado' : (($t['estado'] == 'en_proceso') ? 'En proceso' : 'Abierto');
                                        ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-<?= $eColor ?>-50 text-<?= $eColor ?>-600 border border-<?= $eColor ?>-200">
                                            <?php if ($t['estado'] != 'resuelto'): ?>
                                                <span class="w-1.5 h-1.5 rounded-full bg-<?= $eColor ?>-500 mr-2 animate-pulse"></span>
                                            <?php endif; ?>
                                            <?= $eLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                        <?= date('d M Y', strtotime($t['created_at'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                        Si necesitas añadir detalles extras un ticket existente, por favor manda la peticion a aguilamedez34@gmail.com directamente con nuestro equipo de soporte indicando el número de ticket correspondiente.
                    </p>
                </div>
            </div>
        </div>

    </main>

</body>
</html>