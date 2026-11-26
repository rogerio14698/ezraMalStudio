Nuevo mensaje desde el formulario de la web

Nombre:   {{ $mensaje->name }}
Email:    {{ $mensaje->email }}
Teléfono: {{ $mensaje->phone ?: '—' }}
Asunto:   {{ $mensaje->subject ?: '—' }}

{{ $mensaje->message }}

--
Gestiónalo en el panel: {{ \App\Filament\Resources\ContactMessageResource::getUrl('view', ['record' => $mensaje]) }}
