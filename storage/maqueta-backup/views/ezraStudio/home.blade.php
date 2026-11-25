@extends('app')

@php
    $defaultTitle = 'Lading Page';
    $defaultDescription = 'Lading Page';
    $defaultKeywords = 'Lading Page';

@endphp

@section('title', 'ExitStudio' ?? $defaultTitle)
@section('meta-description', '' ?? $defaultDescription)
@section('meta-keywords', '' ?? $defaultKeywords)

@section('content')
    <section class="hero" id="inicio">
        <div class="container hero__grid">
            <div class="hero__content" data-reveal>
                <span class="badge">Espacio Creativo & Seguro</span>
                <h1 class="hero__title">
                    El color no entiende de normas, <em>solo de expresión.</em>
                </h1>
                <p class="hero__subtitle">
                    Estudio de artes plásticas y taller experimental. Acompañamos procesos creativos para personas adultas e
                    infancias a través del óleo, la acuarela, el acrílico y la técnica mixta.
                </p>
                <div class="hero__actions">
                    <a href="#servicios" class="btn btn--primary">Explorar Talleres</a>
                    <a href="#galeria" class="btn btn--outline">Ver Obra & Alumnado</a>
                </div>
            </div>

            <div class="hero__visual" data-reveal>
                <div class="hero__canvas-frame">
                    <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=900&q=80"
                        alt="Paleta de colores y pinceles en el estudio">
                    <div class="hero__sticker">
                        <span>100%</span>
                        <small>Libre expresión</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MANIFIESTO -->
    <section class="manifesto" id="manifiesto">
        <div class="container manifesto__inner" data-reveal>
            <p class="manifesto__quote">
                “Nacimos como un refugio frente a la academia tradicional. Aquí reivindicamos la sensibilidad
                <span>Queer</span>, el error como hallazgo pictórico y el juego como herramienta de transformación.”
            </p>
            <div class="manifesto__tags">
                <span>#PinturaSinMiedo</span>
                <span>#InfanciasLibres</span>
                <span>#ArteContemporáneo</span>
                <span>#MaterialesIncluidos</span>
            </div>
        </div>
    </section>

    <!-- SERVICIOS (ADULTOS E INFANCIAS) -->
    <section class="services" id="servicios">
        <div class="container">
            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Modalidades y Técnicas</span>
                <h2 class="section-header__title">Talleres a tu ritmo</h2>
                <p class="section-header__desc">Grupos reducidos de máximo 8 personas con seguimiento individualizado según
                    tu nivel e intereses.</p>
            </div>

            <!-- Pestañas de público -->
            <div class="services__tabs" role="tablist" data-reveal>
                <button class="services__tab is-active" data-audience="adultos" role="tab" aria-selected="true">
                    Adultos (+16 años)
                </button>
                <button class="services__tab" data-audience="infantil" role="tab" aria-selected="false">
                    Infancias y Juventud (5 a 15 años)
                </button>
            </div>

            <!-- Grid Adultos -->
            <div class="services__grid is-visible" id="panel-adultos">
                <article class="service-card" data-reveal>
                    <div class="service-card__tag">Técnica Clásica & Contemporánea</div>
                    <h3 class="service-card__title">Óleo y Veladuras</h3>
                    <p class="service-card__text">Aprende a construir atmósferas, trabajar el retrato expresivo y dominar
                        los tiempos de secado y empaste sobre lienzo.</p>
                    <ul class="service-card__list">
                        <li>Teoría del color aplicada</li>
                        <li>Retrato y figura humana</li>
                        <li>Soportes rígidos y tela</li>
                    </ul>
                </article>

                <article class="service-card" data-reveal>
                    <div class="service-card__tag">Fluidez y Mancha</div>
                    <h3 class="service-card__title">Acuarela & Gouache</h3>
                    <p class="service-card__text">Exploración del agua, el pigmento botánico y la ilustración narrativa.
                        Ideal para soltar el trazo y perder el miedo al papel en blanco.</p>
                    <ul class="service-card__list">
                        <li>Húmedo sobre húmedo</li>
                        <li>Cuaderno de artista</li>
                        <li>Ilustración experimental</li>
                    </ul>
                </article>

                <article class="service-card" data-reveal>
                    <div class="service-card__tag">Textura y Volumen</div>
                    <h3 class="service-card__title">Acrílico & Técnica Mixta</h3>
                    <p class="service-card__text">Collage, pasteles grasos, tintas y acrílico de gran formato. Un
                        laboratorio para mezclar lenguajes visuales sin límites.</p>
                    <ul class="service-card__list">
                        <li>Transferencia de imágenes</li>
                        <li>Texturas con cargas minerales</li>
                        <li>Proyectos personales</li>
                    </ul>
                </article>
            </div>

            <!-- Grid Infantil -->
            <div class="services__grid" id="panel-infantil" hidden>
                <article class="service-card service-card--playful">
                    <div class="service-card__tag">5 a 9 años</div>
                    <h3 class="service-card__title">Exploradores del Color</h3>
                    <p class="service-card__text">Iniciación sensorial a la pintura mediante el juego, pigmentos naturales,
                        estampación y creación de personajes propios.</p>
                    <ul class="service-card__list">
                        <li>Desarrollo psicomotriz</li>
                        <li>Cuentos ilustrados</li>
                        <li>Materiales no tóxicos incluidos</li>
                    </ul>
                </article>

                <article class="service-card service-card--playful">
                    <div class="service-card__tag">10 a 15 años</div>
                    <h3 class="service-card__title">Laboratorio Creativo Juvenil</h3>
                    <p class="service-card__text">Un espacio de libertad estética donde experimentar con cómic, pintura
                        acrílica, acuarela y creación de fanzines.</p>
                    <ul class="service-card__list">
                        <li>Narrativa gráfica y personaje</li>
                        <li>Iniciación al lienzo</li>
                        <li>Expresión emocional</li>
                    </ul>
                </article>

                <article class="service-card service-card--playful">
                    <div class="service-card__tag">Fines de semana</div>
                    <h3 class="service-card__title">Talleres en Familia</h3>
                    <p class="service-card__text">Sesiones puntuales de sábado por la mañana para pintar a cuatro manos y
                        compartir un proyecto artístico en casa.</p>
                    <ul class="service-card__list">
                        <li>Murales colaborativos</li>
                        <li>Cerámica fría y pintura</li>
                        <li>Todos los materiales incluidos</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <!-- GALERÍA FILTRABLE -->
    <section class="gallery" id="galeria">
        <div class="container">
            <div class="section-header" data-reveal>
                <span class="section-header__eyebrow">Archivo Visual</span>
                <h2 class="section-header__title">Obra del Estudio & Procesos</h2>
            </div>

            <div class="gallery__filters" data-reveal>
                <button class="filter-btn is-active" data-filter="all">Todo</button>
                <button class="filter-btn" data-filter="oleo">Óleo</button>
                <button class="filter-btn" data-filter="acuarela">Acuarela</button>
                <button class="filter-btn" data-filter="mixta">Técnica Mixta</button>
                <button class="filter-btn" data-filter="taller">Vida en el Taller</button>
            </div>

            <div class="gallery__grid" id="galleryGrid">
                <figure class="gallery-item" data-category="oleo" data-reveal>
                    <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?auto=format&fit=crop&w=800&q=80"
                        alt="Retrato expresionista al óleo" loading="lazy">
                    <figcaption class="gallery-item__caption">
                        <strong>Cuerpos en Tránsito</strong>
                        <span>Óleo sobre lino · 80x100 cm</span>
                    </figcaption>
                </figure>

                <figure class="gallery-item" data-category="acuarela" data-reveal>
                    <img src="https://images.unsplash.com/photo-1541961017774-22349e4a1262?auto=format&fit=crop&w=800&q=80"
                        alt="Composición abstracta en acuarela" loading="lazy">
                    <figcaption class="gallery-item__caption">
                        <strong>Flora Disidente IV</strong>
                        <span>Acuarela y tinta · Papel algodón 300g</span>
                    </figcaption>
                </figure>

                <figure class="gallery-item" data-category="mixta" data-reveal>
                    <img src="https://images.unsplash.com/photo-1547826039-bfc35e0f1ea8?auto=format&fit=crop&w=800&q=80"
                        alt="Pintura acrílica con texturas" loading="lazy">
                    <figcaption class="gallery-item__caption">
                        <strong>Ritmo Cromático</strong>
                        <span>Acrílico y pastel graso · 60x60 cm</span>
                    </figcaption>
                </figure>

                <figure class="gallery-item" data-category="taller" data-reveal>
                    <img src="https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80"
                        alt="Mesa de trabajo con pinceles y colores" loading="lazy">
                    <figcaption class="gallery-item__caption">
                        <strong>Sesión de Sábado</strong>
                        <span>Taller experimental de color</span>
                    </figcaption>
                </figure>

                <figure class="gallery-item" data-category="oleo" data-reveal>
                    <img src="https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?auto=format&fit=crop&w=800&q=80"
                        alt="Paisaje cromático al óleo" loading="lazy">
                    <figcaption class="gallery-item__caption">
                        <strong>Horizonte Violeta</strong>
                        <span>Óleo con espátula · 50x70 cm</span>
                    </figcaption>
                </figure>

                <figure class="gallery-item" data-category="mixta" data-reveal>
                    <img src="https://images.unsplash.com/photo-1515405295579-ba7b45403062?auto=format&fit=crop&w=800&q=80"
                        alt="Collage y pintura experimental" loading="lazy">
                    <figcaption class="gallery-item__caption">
                        <strong>Archivo de Memoria</strong>
                        <span>Collage, gouache y grafito</span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <!-- MODAL LIGHTBOX -->
    <div class="lightbox" id="lightbox" aria-hidden="true">
        <button class="lightbox__close" id="lightboxClose" aria-label="Cerrar vista ampliada">&times;</button>
        <div class="lightbox__content">
            <img src="" alt="" id="lightboxImg">
            <p id="lightboxCaption"></p>
        </div>
    </div>
@endsection
