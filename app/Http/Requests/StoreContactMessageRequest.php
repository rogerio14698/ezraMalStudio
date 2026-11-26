<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Validator;

/**
 * Validación y antispam del formulario de contacto.
 *
 * Tres capas, sin captchas que molesten a las personas:
 *  1. Honeypot: el campo "website" está oculto por CSS; si llega relleno, es un bot.
 *  2. Tiempo: "_ts" lleva la hora de carga cifrada; enviar en menos de N segundos es un bot.
 *  3. Límite por IP: RateLimiter "contacto" en la ruta (AppServiceProvider).
 */
class StoreContactMessageRequest extends FormRequest
{
    /** Ancla a la que vuelve la página si hay errores. */
    protected $errorBag = 'contacto';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\s.-]{6,30}$/'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'privacy' => ['accepted'],
            'website' => ['nullable', 'max:0'],
            '_ts' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'email',
            'phone' => 'teléfono',
            'subject' => 'asunto',
            'message' => 'mensaje',
            'privacy' => 'política de privacidad',
        ];
    }

    public function messages(): array
    {
        return [
            'website.max' => 'No se pudo enviar el mensaje.',
            'privacy.accepted' => 'Debes aceptar la política de privacidad.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                try {
                    $cargado = (int) Crypt::decryptString((string) $this->input('_ts'));
                } catch (DecryptException) {
                    $cargado = 0;
                }

                if ($cargado === 0 || now()->timestamp - $cargado < config('contacto.min_seconds')) {
                    $validator->errors()->add('message', 'No se pudo enviar el mensaje. Espera unos segundos e inténtalo de nuevo.');
                }
            },
        ];
    }

    /** Solo los campos que se guardan (sin honeypot, token ni casilla). */
    public function contactData(): array
    {
        return $this->safe()->only(['name', 'email', 'phone', 'subject', 'message']);
    }

    protected function getRedirectUrl(): string
    {
        return url()->previous().'#contacto';
    }
}
