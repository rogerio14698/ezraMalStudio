{{--
|--------------------------------------------------------------------------
| PIE DE PÁGINA / CONTACTO
|--------------------------------------------------------------------------
| - $estudio (nombre, email, redes) llega de SiteComposer (panel → Pie de página).
| - Formulario: POST /contactos (ContactController). Los mensajes se leen en
|   el panel → Contacto → Mensajes.
| - Antispam: campo trampa "website" (oculto con .contact-form__hp), hora de
|   carga cifrada en "_ts" y límite de envíos por IP.
| - Estilos del formulario: resources/sass/src/_contact.scss
--}}
@php($erroresContacto = $errors->getBag('contacto'))

<footer class="footer" id="contacto">
    <div class="container footer__grid">

        {{-- Bloque de cierre + formulario --}}
        <div class="footer__info">
            <h2 class="footer__title">Reserva tu clase de prueba</h2>
            <p class="footer__text">
                Escríbenos con tu edad (o la de tu peque) y la técnica que te apetece probar.
                Te respondemos con los huecos libres de la semana.
            </p>

            @if (session('contacto_ok'))
                <p class="contact-form__alert contact-form__alert--ok" role="status">{{ session('contacto_ok') }}</p>
            @endif
            @if (session('contacto_error'))
                <p class="contact-form__alert contact-form__alert--error" role="alert">{{ session('contacto_error') }}</p>
            @endif

            <form class="contact-form" method="POST" action="{{ route('contacto.store') }}">
                @csrf
                <input type="hidden" name="_ts" value="{{ \Illuminate\Support\Facades\Crypt::encryptString((string) now()->timestamp) }}">

                {{-- Campo trampa: invisible para personas, los bots lo rellenan --}}
                <div class="contact-form__hp" aria-hidden="true">
                    <label for="contacto-website">No rellenes este campo</label>
                    <input type="text" id="contacto-website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="contact-form__row">
                    <div class="contact-form__field">
                        <label class="contact-form__label" for="contacto-name">Nombre *</label>
                        <input class="contact-form__input" type="text" id="contacto-name" name="name"
                            value="{{ old('name') }}" placeholder="Lucía García" autocomplete="name" maxlength="120" required
                            @if ($erroresContacto->has('name')) aria-invalid="true" aria-describedby="contacto-name-error" @endif>
                        @if ($erroresContacto->has('name'))
                            <p class="contact-form__error" id="contacto-name-error">{{ $erroresContacto->first('name') }}</p>
                        @endif
                    </div>
                    <div class="contact-form__field">
                        <label class="contact-form__label" for="contacto-email">Email *</label>
                        <input class="contact-form__input" type="email" id="contacto-email" name="email"
                            value="{{ old('email') }}" placeholder="lucia@ejemplo.com" autocomplete="email" required
                            @if ($erroresContacto->has('email')) aria-invalid="true" aria-describedby="contacto-email-error" @endif>
                        @if ($erroresContacto->has('email'))
                            <p class="contact-form__error" id="contacto-email-error">{{ $erroresContacto->first('email') }}</p>
                        @endif
                    </div>
                </div>

                <div class="contact-form__row">
                    <div class="contact-form__field">
                        <label class="contact-form__label" for="contacto-phone">Teléfono</label>
                        <input class="contact-form__input" type="tel" id="contacto-phone" name="phone"
                            value="{{ old('phone') }}" placeholder="+34 600 123 456" autocomplete="tel" maxlength="30"
                            @if ($erroresContacto->has('phone')) aria-invalid="true" aria-describedby="contacto-phone-error" @endif>
                        @if ($erroresContacto->has('phone'))
                            <p class="contact-form__error" id="contacto-phone-error">{{ $erroresContacto->first('phone') }}</p>
                        @endif
                    </div>
                    <div class="contact-form__field">
                        <label class="contact-form__label" for="contacto-subject">Asunto</label>
                        <input class="contact-form__input" type="text" id="contacto-subject" name="subject"
                            value="{{ old('subject') }}" placeholder="Clase de prueba de acuarela" maxlength="150">
                    </div>
                </div>

                <div class="contact-form__field">
                    <label class="contact-form__label" for="contacto-message">Mensaje *</label>
                    <textarea class="contact-form__input contact-form__textarea" id="contacto-message" name="message"
                        placeholder="Tengo 34 años, nunca he pintado al óleo y me vendrían bien las tardes…" minlength="10"
                        maxlength="5000" required
                        @if ($erroresContacto->has('message')) aria-invalid="true" aria-describedby="contacto-message-error" @endif>{{ old('message') }}</textarea>
                    @if ($erroresContacto->has('message'))
                        <p class="contact-form__error" id="contacto-message-error">{{ $erroresContacto->first('message') }}</p>
                    @endif
                </div>

                <div class="contact-form__field">
                    <label class="contact-form__check">
                        <input type="checkbox" name="privacy" value="1" required @checked(old('privacy'))>
                        <span>Acepto que guardéis mis datos solo para responder a este mensaje.</span>
                    </label>
                    @if ($erroresContacto->has('privacy'))
                        <p class="contact-form__error">{{ $erroresContacto->first('privacy') }}</p>
                    @endif
                </div>

                <button type="submit" class="btn btn--light contact-form__submit">Enviar mensaje</button>
            </form>
        </div>

        {{-- Columnas de información --}}
        <div class="footer__meta">
            <div class="footer__col">
                <h3>Contacto</h3>
                <ul class="footer__socials">
                    @if ($estudio['email'])
                        <li><a href="mailto:{{ $estudio['email'] }}">{{ $estudio['email'] }}</a></li>
                    @endif
                    @if ($estudio['telefono'])
                        <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $estudio['telefono']) }}">{{ $estudio['telefono'] }}</a></li>
                    @endif
                </ul>
            </div>
            <div class="footer__col">
                <h3>Comunidad</h3>
                <ul class="footer__socials">
                    @foreach ($estudio['redes'] as $red)
                        <li><a href="{{ $red['url'] }}" target="_blank" rel="noopener">{{ $red['nombre'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer__col">
                <h3>Navegación</h3>
                <ul class="footer__socials">
                    <li><a href="#visita">Horarios</a></li>
                    <li><a href="#talleres">Talleres</a></li>
                    <li><a href="#galeria">Galería</a></li>
                </ul>
            </div>
        </div>

    </div>

    <div class="container footer__bottom">
        <p>{{ $estudio['copyright'] ?: '© ' . date('Y') . ' ' . $estudio['nombre'] . '.' }}</p>
    </div>
</footer>
