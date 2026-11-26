<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\NuevoMensajeContacto;
use App\Models\Contacto\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * POST /contactos: guarda el mensaje (se gestiona en el panel) y avisa por email.
 */
class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $volver = url()->previous().'#contacto';

        try {
            $mensaje = ContactMessage::create($request->contactData() + [
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
            ]);
        } catch (\Throwable $e) {
            Log::error('No se pudo guardar el mensaje de contacto', ['error' => $e->getMessage()]);

            return redirect()->to($volver)
                ->withInput()
                ->with('contacto_error', 'No hemos podido enviar tu mensaje. Inténtalo más tarde o escríbenos por email.');
        }

        // El aviso por email es secundario: si falla, el mensaje ya está guardado en el panel.
        if ($destino = config('contacto.mail_to')) {
            try {
                Mail::to($destino)->send(new NuevoMensajeContacto($mensaje));
            } catch (\Throwable $e) {
                Log::warning('Mensaje guardado pero no se pudo enviar el aviso por email', [
                    'id' => $mensaje->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // "contacto_ok" también dispara la conversión de Google Ads en el layout.
        return redirect()->to($volver)
            ->with('contacto_ok', 'Gracias, hemos recibido tu mensaje. Te responderemos en 24-48 h.');
    }
}
