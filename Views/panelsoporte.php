<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Soporte - TicketSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] font-sans text-slate-900">

    <?php include 'Views/partials/navbar.php'; ?>

    <main class="max-w-7xl mx-auto mt-12 px-6 pb-20">
        
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Panel de Control</h1>
            <p class="text-slate-500 mt-2 text-lg">Gestión global de todas las incidencias del sistema.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Pendientes</p>
                <p class="text-3xl font-black text-slate-800"><?= $stats['pendientes'] ?> Casos</p>
            </div>
            
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Críticos</p>
                <p class="text-3xl font-black text-red-600"><?= $stats['criticos'] ?> Tickets</p>
            </div>
            
            <div class="bg-emerald-500 p-8 rounded-3xl shadow-lg shadow-emerald-200 text-white">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-widest mb-1">Resueltos Hoy</p>
                <p class="text-3xl font-black"><?= $stats['resueltos_hoy'] ?> Éxitos</p>
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
                        <?php foreach ($tickets as $t): ?>
                            <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-cyan-100 text-cyan-600 rounded-xl flex items-center justify-center font-bold text-xs">
                                            <?= strtoupper(substr($t['email'], 0, 2)) ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-slate-800 font-bold text-sm leading-tight truncate max-w-[150px]"><?= htmlspecialchars($t['email']) ?></span>
                                            <span class="text-[10px] text-slate-400">ID: #TK-<?= $t['id'] ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-slate-700 font-medium text-sm"><?= htmlspecialchars($t['asunto']) ?></span>
                                        <span class="text-[10px] text-cyan-500 font-bold uppercase mt-1"><?= str_replace('_', ' DE ', strtoupper($t['ubicacion'])) ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php 
                                        $pColor = ($t['prioridad'] == 'alta') ? 'red' : (($t['prioridad'] == 'media') ? 'blue' : 'emerald');
                                    ?>
                                    <span class="px-3 py-1 rounded text-[10px] font-bold uppercase bg-<?= $pColor ?>-50 text-<?= $pColor ?>-600 border border-<?= $pColor ?>-100"><?= $t['prioridad'] ?></span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php 
                                        $eColor = ($t['estado'] == 'resuelto') ? 'emerald' : (($t['estado'] == 'en_proceso') ? 'amber' : 'slate');
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-<?= $eColor ?>-50 text-<?= $eColor ?>-600 border border-<?= $eColor ?>-200"><?= str_replace('_', ' ', $t['estado']) ?></span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <form action="index.php?action=actualizar_estado" method="POST" class="flex items-center justify-end gap-2">
                                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                        <select name="estado" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold p-1 outline-none cursor-pointer">
                                            <option value="abierto" <?= $t['estado'] == 'abierto' ? 'selected' : '' ?>>Abierto</option>
                                            <option value="en_proceso" <?= $t['estado'] == 'en_proceso' ? 'selected' : '' ?>>Procesando</option>
                                            <option value="resuelto" <?= $t['estado'] == 'resuelto' ? 'selected' : '' ?>>Resuelto</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($tickets)): ?>
                            <tr><td colspan="5" class="px-8 py-10 text-center text-slate-400 italic">No hay tickets registrados en el sistema.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>