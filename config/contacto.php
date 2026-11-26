<?php

/*
|--------------------------------------------------------------------------
| Formulario público de contacto (POST /contactos)
|--------------------------------------------------------------------------
| mail_to:      a quién se avisa por email de cada mensaje nuevo (vacío = no se envía).
| min_seconds:  tiempo mínimo entre cargar la página y enviar; los bots lo hacen al instante.
| max_por_minuto: envíos permitidos por IP y minuto (RateLimiter "contacto").
*/

return [
    'mail_to' => env('CONTACT_MAIL_TO'),
    'min_seconds' => (int) env('CONTACT_MIN_SECONDS', 3),
    'max_por_minuto' => (int) env('CONTACT_MAX_PER_MINUTE', 3),
];
