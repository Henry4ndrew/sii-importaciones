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
<div class="w-full flex flex-wrap gap-6">
     <div class="w-full">
        <h1 class="text-2xl font-extrabold text-primary-50 mb-1">Publicar Nuevo Producto</h1>
        <p class="text-primary-50 text-sm">Dos formas de publicar, las dos automáticas.</p>
    </div>


    <!-- Opción 2: pegar la URL -->
    <div class="flex-1 min-w-[280px] bg-primary-700/90 rounded-2xl p-6 border border-white/20 shadow-2xl">
        <h2 class="font-bold text-white text-lg mb-2">Publicar producto</h2>
        <p class="text-primary-50 text-sm mb-4">
            El sistema extrae los datos automáticamente. Si Alibaba bloquea la lectura, el nombre se genera desde la propia URL.
        </p>
        <form method="POST" action="<?= url('productos') ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-sky mb-1">URL del producto de Alibaba</label>
                <input type="url" name="url" required placeholder="https://www.alibaba.com/product-detail/..."
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3 text-white placeholder:text-white/50 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all duration-200">
            </div>
            <button  class="w-full px-4 py-3 rounded-xl font-bold text-white flex items-center justify-center gap-2 bg-gradient-to-r from-[#00eeff] to-primary-500 hover:from-primary-900 hover:to-[#00eeff] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)]">
                <i class="fas fa-cloud-arrow-down text-sm"></i>
                <span>Extraer y Publicar Producto</span>
            </button>
        </form>
    </div>

   <!-- Opción 1: botón de captura de 1 clic (datos completos) -->
    <div class="flex-1 min-w-[280px] bg-primary-700/90 rounded-2xl p-6 border border-white/20 shadow-2xl">
         <div class="flex items-center gap-2 mb-2">
            <span class="bg-primary-400 text-white text-xs font-black rounded px-2 py-0.5">RECOMENDADO</span>
            <h2 class="font-bold text-white text-lg">Captura de 1 clic con todos los detalles</h2>
        </div>
        <p class="text-primary-50 text-sm mb-4">
            Captura <span class="font-semibold text-sky">foto, precio, precio original y descuento</span> directamente
            desde la página de Alibaba. Instálalo una sola vez:
        </p>
        <ol class="text-primary-50 text-sm list-decimal list-inside space-y-1 mb-4">
            <li>Arrastra este botón a tu barra de marcadores (Ctrl+Shift+B si está oculta):</li>
        </ol>
        <div class="text-center mb-4">
        <a href="<?= e($bookmarklet) ?>"
        onclick="alert('No hagas clic aquí: arrástralo a tu barra de marcadores.\n\nLuego, en la página del producto de Alibaba, haz clic en el marcador y se publicará solo.'); return false;"
        class="w-full px-4 py-3 rounded-xl font-bold text-white flex items-center justify-center gap-2 bg-gradient-to-r from-[#00eeff] to-primary-500 hover:from-primary-900 hover:to-[#00eeff] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)]">
            <i class="fa-solid fa-bookmark"></i>
            Publicar en Sii importaciones
        </a>
        </div>
        <ol start="2" class="text-sky text-sm list-decimal list-inside space-y-1">
            <li>Navega al producto en <span class="font-semibold text-white">alibaba.com</span>.</li>
            <li>Haz clic en el marcador <span class="font-semibold text-white">"📌 Publicar en Sii importaciones"</span> — el producto se publica solo, con todos sus datos.</li>
        </ol>
    </div>

    <!-- Footer decorativo -->
     <div class="w-full text-center text-primary-50/80 text-xs border-t border-white/10 pt-4">
       <i class="fas fa-shield-alt mr-1"></i> Los datos se extraen automáticamente desde Alibaba
    </div>

</div>
