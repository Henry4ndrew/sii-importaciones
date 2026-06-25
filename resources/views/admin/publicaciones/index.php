<?php
// Verificar que sea administrador
if (!isset($_SESSION['administrador'])) {
    header('Location: ' . url('auth/login'));
    exit;
}

// Obtener configuración del login (si no está definida, crear valores por defecto)
$configLogin = $configLogin ?? [
    'login_habilitado' => true,
    'mensaje' => 'El sistema de acceso para usuarios se encuentra temporalmente deshabilitado. Por favor, intenta más tarde.'
];
?>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <!-- ============================================ -->
    <!-- SECCIÓN DE CONFIGURACIÓN DEL LOGIN (SIEMPRE VISIBLE) -->
    <!-- ============================================ -->
    <div class="bg-primary-800 from-primary-50 to-slate-50 border-b border-slate-200">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-lock text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-slate-800">Configuración de Acceso de Usuarios</h3>
                        <p class="text-xs text-primary-100">Controla el acceso de usuarios normales al sistema</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Estado actual con animación -->
                    <span id="estadoBadge" class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border transition-all duration-300
                        <?= (($configLogin['login_habilitado'] ?? true) 
                            ? 'bg-green-100 text-green-700 border-green-200' 
                            : 'bg-red-100 text-red-700 border-red-200') ?>">
                        <i class="fas <?= (($configLogin['login_habilitado'] ?? true) ? 'fa-check-circle' : 'fa-times-circle') ?>"></i>
                        <span id="estadoTexto">
                            <?= (($configLogin['login_habilitado'] ?? true) ? 'Habilitado' : 'Deshabilitado') ?>
                        </span>
                    </span>
                    
                    <!-- Indicador de guardado -->
                    <span id="indicadorGuardado" class="text-xs text-slate-400 hidden">
                        <i class="fas fa-spinner fa-spin"></i> Guardando...
                    </span>
                </div>
            </div>

            <?php if ($flash = getFlash()): ?>
                <div class="mt-4 rounded-xl px-4 py-3 text-sm font-semibold border
                    <?= $flash['tipo'] === 'exito' 
                        ? 'bg-green-50 text-green-700 border-green-200' 
                        : 'bg-red-50 text-red-700 border-red-200' ?>">
                    <?= e($flash['mensaje']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('admin/publicaciones/configuracion/actualizar') ?>" id="formConfiguracion" class="mt-4 bg-white rounded-lg p-4 border border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda: Switch con auto-guardado -->
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-800">Estado del Login</h4>
                            <p class="text-xs text-slate-500 mt-1">
                                <span id="estadoDescripcion" class="font-medium <?= (($configLogin['login_habilitado'] ?? true) ? 'text-green-600' : 'text-red-600') ?>">
                                    <?= (($configLogin['login_habilitado'] ?? true) ? '✅ Usuarios pueden iniciar sesión' : '❌ Usuarios NO pueden iniciar sesión') ?>
                                </span>
                            </p>
                        </div>
                        <div class="flex items-center">
                            <!-- Campo oculto para cuando el switch está desactivado -->
                            <input type="hidden" name="login_habilitado" value="0">
                            
                            <!-- Switch moderno con auto-guardado -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="login_habilitado" 
                                       value="1"
                                       id="loginSwitch"
                                       <?= (($configLogin['login_habilitado'] ?? true) ? 'checked' : '') ?>
                                       class="sr-only peer">
                                <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer 
                                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                            after:bg-white after:border-slate-300 after:border after:rounded-full 
                                            after:h-6 after:w-6 after:transition-all peer-checked:bg-primary-500">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Columna Derecha: Mensaje -->
                    <div class="space-y-2">
                        <label for="mensaje" class="block text-sm font-medium text-slate-700">
                            <i class="fas fa-message mr-1 text-primary-500"></i>
                            Mensaje cuando el login está deshabilitado
                        </label>
                        <div class="flex gap-2">
                            <textarea 
                                id="mensaje"
                                name="mensaje" 
                                rows="2"
                                class="flex-1 border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition resize-y"
                                placeholder="Escribe el mensaje que verán los usuarios..."
                            ><?= e($configLogin['mensaje'] ?? 'El sistema de acceso para usuarios se encuentra temporalmente deshabilitado. Por favor, intenta más tarde.') ?></textarea>
                        </div>
                        <p class="text-xs text-slate-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            El switch se guarda automáticamente al cambiarlo
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- GESTIÓN DE PUBLICACIONES                     -->
    <!-- ============================================ -->
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between flex-wrap gap-3">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-newspaper mr-2 text-primary-500"></i> Gestión de Publicaciones
        </h2>
        <span class="text-sm text-slate-500">Total: <?= count($publicaciones) ?> publicaciones</span>
    </div>

    <!-- Filtros -->
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
        <form method="GET" action="<?= url('admin/publicaciones') ?>" class="flex flex-wrap items-center gap-3">
            <!-- Búsqueda -->
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="buscar" value="<?= e($busqueda ?? '') ?>" 
                       placeholder="Buscar por título o usuario..."
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
            </div>

            <!-- Filtro por votos -->
            <div class="min-w-[150px]">
                <select name="votos" class="w-full border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <option value="todos" <?= ($filtroVotos ?? 'todos') === 'todos' ? 'selected' : '' ?>>Todos los votos</option>
                    <option value="0" <?= ($filtroVotos ?? '') === '0' ? 'selected' : '' ?>>Sin votos</option>
                    <option value="1-5" <?= ($filtroVotos ?? '') === '1-5' ? 'selected' : '' ?>>1 - 5 votos</option>
                    <option value="6-10" <?= ($filtroVotos ?? '') === '6-10' ? 'selected' : '' ?>>6 - 10 votos</option>
                    <option value="10+" <?= ($filtroVotos ?? '') === '10+' ? 'selected' : '' ?>>Más de 10 votos</option>
                </select>
            </div>

            <!-- Filtro por fecha -->
            <div class="min-w-[150px]">
                <select name="fecha" class="w-full border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <option value="todos" <?= ($filtroFecha ?? 'todos') === 'todos' ? 'selected' : '' ?>>Todas las fechas</option>
                    <option value="hoy" <?= ($filtroFecha ?? '') === 'hoy' ? 'selected' : '' ?>>Hoy</option>
                    <option value="semana" <?= ($filtroFecha ?? '') === 'semana' ? 'selected' : '' ?>>Última semana</option>
                    <option value="mes" <?= ($filtroFecha ?? '') === 'mes' ? 'selected' : '' ?>>Último mes</option>
                </select>
            </div>

            <!-- Botones -->
            <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="<?= url('admin/publicaciones') ?>" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-undo mr-1"></i> Limpiar
            </a>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Publicado por</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Votos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($publicaciones)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-slate-500">No hay publicaciones que coincidan con los filtros</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($publicaciones as $pub): ?>
                        <tr class="hover:bg-primary-50/50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">#<?= $pub['id'] ?></td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($pub['imagen'])): ?>
                                        <img src="<?= $pub['imagen'] ?>" 
                                             alt="<?= e($pub['nombre']) ?>" 
                                             class="w-10 h-10 object-cover rounded-lg border border-slate-200">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-slate-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="font-medium truncate max-w-[200px]"><?= e($pub['nombre']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?= e($pub['publicado_por']) ?></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?= ($pub['total_votos'] ?? 0) > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500' ?>">
                                    <i class="fas fa-star text-xs"></i>
                                    <?= $pub['total_votos'] ?? 0 ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500"><?= date('d/m/Y H:i', strtotime($pub['created_at'])) ?></td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/publicaciones/ver') ?>?id=<?= $pub['id'] ?>" 
                                       class="text-primary-500 hover:text-primary-700 transition"
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= url('admin/publicaciones/eliminar') ?>?id=<?= $pub['id'] ?>" 
                                       onclick="return confirm('¿Estás seguro de eliminar esta publicación? Esta acción eliminará también todos los votos asociados.')"
                                       class="text-red-600 hover:text-red-800 transition"
                                       title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript para auto-guardado del switch -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const switchInput = document.getElementById('loginSwitch');
    const form = document.getElementById('formConfiguracion');
    const estadoDescripcion = document.getElementById('estadoDescripcion');
    const estadoBadge = document.getElementById('estadoBadge');
    const estadoTexto = document.getElementById('estadoTexto');
    const indicadorGuardado = document.getElementById('indicadorGuardado');
    
    // Función para actualizar la UI inmediatamente (optimista)
    function actualizarUI(habilitado) {
        // Actualizar descripción
        if (estadoDescripcion) {
            estadoDescripcion.textContent = habilitado ? '✅ Usuarios pueden iniciar sesión' : '❌ Usuarios NO pueden iniciar sesión';
            estadoDescripcion.className = 'font-medium ' + (habilitado ? 'text-green-600' : 'text-red-600');
        }
        
        // Actualizar badge
        if (estadoBadge) {
            estadoBadge.className = 'inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border transition-all duration-300 ' + 
                (habilitado 
                    ? 'bg-green-100 text-green-700 border-green-200' 
                    : 'bg-red-100 text-red-700 border-red-200');
        }
        
        // Actualizar texto del badge
        if (estadoTexto) {
            estadoTexto.textContent = habilitado ? 'Habilitado' : 'Deshabilitado';
        }
    }
    
    // Función para mostrar indicador de guardado
    function mostrarIndicador(mostrar) {
        if (indicadorGuardado) {
            if (mostrar) {
                indicadorGuardado.classList.remove('hidden');
            } else {
                indicadorGuardado.classList.add('hidden');
            }
        }
    }
    
    // Función para enviar el formulario vía AJAX
    function guardarConfiguracion(habilitado) {
        // Mostrar indicador de guardado
        mostrarIndicador(true);
        
        // Obtener el mensaje actual
        const mensaje = document.getElementById('mensaje').value;
        
        // Crear FormData
        const formData = new FormData();
        formData.append('login_habilitado', habilitado ? '1' : '0');
        formData.append('mensaje', mensaje);
        
        // Enviar petición AJAX
        fetch('<?= url('admin/publicaciones/configuracion/actualizar') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            // Ocultar indicador de guardado
            mostrarIndicador(false);
            
            if (response.ok) {
                // Mostrar feedback visual de éxito
                if (estadoBadge) {
                    estadoBadge.style.transition = 'all 0.3s ease';
                    estadoBadge.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        estadoBadge.style.transform = 'scale(1)';
                    }, 200);
                }
            } else {
                // Si hay error, revertir el switch
                switchInput.checked = !habilitado;
                actualizarUI(!habilitado);
                
                // Mostrar mensaje de error (usando flash o alert)
                alert('Error al guardar la configuración. Por favor, intenta nuevamente.');
            }
        })
        .catch(error => {
            // Ocultar indicador de guardado
            mostrarIndicador(false);
            
            // Revertir el switch en caso de error
            switchInput.checked = !habilitado;
            actualizarUI(!habilitado);
            
            console.error('Error:', error);
            alert('Error al guardar la configuración. Por favor, intenta nuevamente.');
        });
    }
    
    // Evento: cambio en el switch
    if (switchInput) {
        switchInput.addEventListener('change', function(e) {
            const habilitado = this.checked;
            
            // Actualizar UI inmediatamente (feedback optimista)
            actualizarUI(habilitado);
            
            // Guardar la configuración
            guardarConfiguracion(habilitado);
        });
    }
    
    // Evento: envío del formulario (para el botón Guardar)
    if (form) {
        form.addEventListener('submit', function(e) {
            // Si el formulario se envía normalmente, permitir el envío
            // No prevenir el comportamiento por defecto
            // El botón Guardar seguirá funcionando como respaldo
        });
    }
    
    // Inicializar UI con el estado actual
    const estadoInicial = <?= (($configLogin['login_habilitado'] ?? true) ? 'true' : 'false') ?>;
    actualizarUI(estadoInicial);
    
    // Auto-guardar el mensaje cuando se cambia (opcional)
    const mensajeTextarea = document.getElementById('mensaje');
    let timeoutId = null;
    
    if (mensajeTextarea) {
        mensajeTextarea.addEventListener('input', function() {
            // Esperar 1 segundo después de dejar de escribir para guardar
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                // Solo guardar si el switch está deshabilitado (para actualizar el mensaje)
                if (!switchInput.checked) {
                    guardarConfiguracion(false);
                }
            }, 1000);
        });
    }
});
</script>

<!-- Estilos CSS adicionales para mejorar el feedback -->
<style>
/* Animación del badge al guardar */
@keyframes pulse-badge {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Transición suave para el switch */
.peer:checked ~ .peer-checked\:bg-primary-500 {
    --tw-bg-opacity: 1;
    background-color: rgb(59 130 246 / var(--tw-bg-opacity));
}

.peer:focus ~ .peer-focus\:ring-4 {
    --tw-ring-opacity: 1;
    --tw-ring-color: rgb(147 197 253 / var(--tw-ring-opacity));
}

/* Animación de carga del indicador */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

/* Feedback visual al cambiar el switch */
#estadoBadge {
    transition: all 0.3s ease;
}
</style>