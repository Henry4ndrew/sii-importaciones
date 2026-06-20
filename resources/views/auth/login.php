<div class="max-w-md mx-auto mt-8">
    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-2xl font-extrabold text-slate-800 text-center mb-1">Acceder al Sistema</h1>
        <p class="text-slate-500 text-sm text-center mb-6">Inicia sesión para publicar y votar productos</p>

        <form method="POST" action="<?= url('login') ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" required
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Contraseña</label>
                <input type="password" name="password" required
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button class="w-full bg-slate-900 text-white font-bold py-3 rounded-lg hover:bg-slate-700 transition">
                Iniciar Sesión
            </button>
        </form>

        <p class="text-sm text-center text-slate-500 mt-5">
            ¿No tienes cuenta?
            <a href="<?= url('register') ?>" class="text-amber-600 font-bold hover:underline">Regístrate aquí</a>
        </p>
    </div>
</div>
