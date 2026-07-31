<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - Inicio</title>
    @vite(['resources/css/app.css'])
</head>
<body class="home-body">

    @include('loader')

    <x-navbar />

    <main class="hero">
        <div class="hero__container">
            
            <section class="hero__content">
                <h1 class="hero__title">
                    Pharenia<br><span class="hero__title--highlight">El TEA.</span>
                </h1>
                
                <p class="hero__description">
                    Pharenia es un lugar con el fin de potenciar las capacidades naturales de personas con Trastorno del Espectro Autista.
                </p>

                <div class="hero__actions">
                    <a href="/activities" class="hero__btn hero__btn--primary">EXPLORAR ACTIVIDADES</a>
                    <a href="/information" class="hero__btn hero__btn--secondary">CONOCER MÁS</a>
                </div>
            </section>

            <div class="hero__character-container">
                <img src="{{ asset('img/glow.png') }}" alt="" class="hero__layer hero__layer--glow">
                
                <img src="{{ asset('img/feather1.png') }}" alt="" class="hero__layer hero__layer--feather-1">
                <img src="{{ asset('img/feather2.png') }}" alt="" class="hero__layer hero__layer--feather-2">
                <img src="{{ asset('img/feather3.png') }}" alt="" class="hero__layer hero__layer--feather-3">
                
                <img src="{{ asset('img/lumen.png') }}" alt="Lumen" class="hero__layer hero__layer--lumen">
                
                <img src="{{ asset('img/cloud.png') }}" alt="" class="hero__layer hero__layer--cloud">
            </div>

        </div>
    </main>

</body>
</html>