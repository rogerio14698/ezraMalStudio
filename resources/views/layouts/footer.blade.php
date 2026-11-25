{{--
|--------------------------------------------------------------------------
| PIE DE PÁGINA / CONTACTO
|--------------------------------------------------------------------------
| - $estudio (nombre, email, redes) viene de app.blade.php
| - Es la sección de cierre: invita a escribir para reservar.
| - Créditos de fotos: una sola línea al final (fotos provisionales de Unsplash).
--}}

<footer class="footer" id="contacto">
    <div class="container footer__grid">

        {{-- Bloque de cierre --}}
        <div class="footer__info">
            <h2 class="footer__title">Reserva tu clase de prueba</h2>
            <p class="footer__text">
                Escríbenos con tu edad (o la de tu peque) y la técnica que te apetece probar.
                Te respondemos con los huecos libres de la semana.
            </p>
            <a href="mailto:{{ $estudio['email'] }}" class="btn btn--light">Escribir a {{ $estudio['email'] }}</a>
        </div>

        {{-- Columnas de información --}}
        <div class="footer__meta">
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
        <p>&copy; {{ date('Y') }} {{ $estudio['nombre'] }}.</p>
        {{-- Quitar cuando se usen fotos propias --}}
        <p>Fotografías provisionales: Unsplash.</p>
    </div>
</footer>
