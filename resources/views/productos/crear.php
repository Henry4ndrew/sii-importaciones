<?php
// URL absoluta del endpoint de captura, para incrustarla en el botón
$captureUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
    . '://' . $_SERVER['HTTP_HOST'] . url('productos/capturar');

// Botón de 1 clic: se ejecuta DENTRO de la página de Alibaba (sin bloqueo anti-bots),
// lee nombre, imagen, precio, precio original y descuento, y los envía al sistema
$js = <<<'JS'
javascript:(function(){var d=document;function g(q){var e=d.querySelector('meta[property="'+q+'"]');return e?e.content:''}var n=(g('og:title')||d.title).split(' - Buy ')[0].replace(/\s+/g,' ').trim();var i=g('og:image')||'';var p='';var pe=d.querySelector('[class*="module_price"]');if(pe){var raw=(pe.innerText||'').split(/Cantidad|Minimum|MOQ/)[0].replace(/\n/g,' ').trim();p=raw.replace(/(\d+)\s+(\d{2})(?!\d)/g,'$1,$2').replace(/\s*-\s*/,' - ').replace(/\s{2,}/g,' ').trim()}var t=(d.body&&d.body.innerText)||'';if(!p){var m=t.match(/(US\s?|Bs\s?)?\$?\s?\d[\d.,]*\s?-\s?\d[\d.,]*/);if(m)p=m[0]}var pm=(t.match(/Cantidad mínima[^:]*:\s*([^\n]+)/i)||t.match(/Min(?:imum)?\.?\s?order[^:]*:\s*([^\n]+)/i)||[null,null]);var pmin=((pm[1]||'')+'').trim();var po='',of='';var dl=d.querySelector('del,s');if(dl&&/\d/.test(dl.innerText))po=dl.innerText.trim();var mo=t.match(/\d{1,3}\s?%\s?(off|OFF|Off|dcto|dto\.?|descuento)/);if(mo)of=mo[0].trim();if(!i){var im=d.querySelector('img[src*="alicdn"]');if(im)i=im.src}location.href='__CAPTURAR__?url='+encodeURIComponent(location.href.split('#')[0])+'&nombre='+encodeURIComponent(n)+'&imagen='+encodeURIComponent(i)+'&precio='+encodeURIComponent(p)+'&precio_original='+encodeURIComponent(po)+'&oferta='+encodeURIComponent(of)+'&pedido_minimo='+encodeURIComponent(pmin)})();
JS;
$bookmarklet = str_replace('__CAPTURAR__', $captureUrl, $js);
?>
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 mb-1">Publicar Nuevo Producto</h1>
        <p class="text-slate-500 text-sm">Dos formas de publicar, las dos automáticas.</p>
    </div>

    <!-- Opción 1: botón de captura de 1 clic (datos completos) -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-amber-500 text-slate-900 text-xs font-black rounded px-2 py-0.5">RECOMENDADO</span>
            <h2 class="font-bold text-slate-800">Captura de 1 clic con todos los detalles</h2>
        </div>
        <p class="text-sm text-slate-500 mb-4">
            Captura <span class="font-semibold">foto, precio, precio original y descuento</span> directamente
            desde la página de Alibaba. Instálalo una sola vez:
        </p>
        <ol class="text-sm text-slate-600 list-decimal list-inside space-y-1 mb-4">
            <li>Arrastra este botón a tu barra de marcadores (Ctrl+Shift+B si está oculta):</li>
        </ol>
        <div class="text-center mb-4">
            <a href="<?= e($bookmarklet) ?>"
               onclick="alert('No hagas clic aquí: arrástralo a tu barra de marcadores.\n\nLuego, en la página del producto de Alibaba, haz clic en el marcador y se publicará solo.'); return false;"
               class="inline-block px-5 py-2.5 rounded-lg bg-slate-900 text-white font-bold cursor-move select-none hover:bg-slate-700">
                📌 Publicar en WILLS
            </a>
        </div>
        <ol start="2" class="text-sm text-slate-600 list-decimal list-inside space-y-1">
            <li>Navega al producto en <span class="font-semibold">alibaba.com</span>.</li>
            <li>Haz clic en el marcador <span class="font-semibold">"📌 Publicar en WILLS"</span> — el producto se publica solo, con todos sus datos.</li>
        </ol>
    </div>

    <!-- Opción 2: pegar la URL -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-bold text-slate-800 mb-2">O pega la URL del producto</h2>
        <p class="text-sm text-slate-500 mb-4">
            El sistema extrae los datos automáticamente. Si Alibaba bloquea la lectura, el nombre se genera desde la propia URL.
        </p>
        <form method="POST" action="<?= url('productos') ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">URL del producto de Alibaba</label>
                <input type="url" name="url" required placeholder="https://www.alibaba.com/product-detail/..."
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button class="w-full bg-amber-500 text-slate-900 font-bold py-3 rounded-lg hover:bg-amber-400 transition">
                Extraer y Publicar Producto
            </button>
        </form>
    </div>
</div>
