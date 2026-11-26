<?php

/*
|--------------------------------------------------------------------------
| Optimización de medios (MediaOptimizationService)
|--------------------------------------------------------------------------
| Cada imagen subida genera variantes WebP (y AVIF si el servidor lo soporta)
| en estos anchos. Nunca se amplía una imagen más pequeña que el ancho pedido.
*/

return [

    // Disco donde se guardan originales y variantes (debe ser público).
    'disk' => env('MEDIA_DISK', 'public'),

    // Anchos en píxeles, de menor a mayor.
    'breakpoints' => [
        'sm' => 480,
        'md' => 960,
        'lg' => 1600,
    ],

    // Calidad de compresión (0-100). AVIF necesita menos calidad para igual nitidez.
    'quality' => [
        'webp' => (int) env('MEDIA_WEBP_QUALITY', 80),
        'avif' => (int) env('MEDIA_AVIF_QUALITY', 60),
    ],

    // Permite desactivar AVIF (codificar AVIF con GD es lento en servidores modestos).
    'avif' => (bool) env('MEDIA_AVIF', true),

    // Extensiones que se optimizan. SVG (vectorial) y GIF (animado) se dejan tal cual.
    'optimizable_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'avif', 'bmp'],

];
