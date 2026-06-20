<div class="max-w-md mx-auto mt-8">
    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-2xl font-extrabold text-slate-800 text-center mb-1">Crear Cuenta</h1>
        <p class="text-slate-500 text-sm text-center mb-6">Únete a la comunidad de WILLS IMPORT</p>

        <form method="POST" action="<?= url('register') ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nombre</label>
                <input type="text" name="nombre" required
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" required
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Contraseña (mínimo 6 caracteres)</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button class="w-full bg-amber-500 text-slate-900 font-bold py-3 rounded-lg hover:bg-amber-400 transition">
                Registrarme
            </button>
        </form>

        <p class="text-sm text-center text-slate-500 mt-5">
            ¿Ya tienes cuenta?
            <a href="<?= url('login') ?>" class="text-amber-600 font-bold hover:underline">Inicia sesión</a>
        </p>
    </div>
</div>
