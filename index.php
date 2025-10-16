<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div>
    <div id="carouselInicialSC" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselInicialSC" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselInicialSC" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselInicialSC" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/prestadoresSliderbar.jpg" class="d-block w-100" style="max-height: 500px; object-fit: cover;" alt="Seja um Prestador de Serviços">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Seja um Prestador de Serviços</h2>
                    <p>Junte-se a nós e ofereça seus serviços de limpeza.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/sliderbarcliente.jpg" class="d-block w-100" style="max-height: 500px; object-fit: cover;" alt="Seja um Cliente">
                <div class="carousel-caption d-none d-md-block">
                    <h2 style="color: black; text-shadow: 1px 1px 2px white;">Seja um dos nossos Clientes</h2>
                    <p style="color: black; text-shadow: 1px 1px 2px white;">Encontre os melhores prestadores de serviços de limpeza.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/feedbackCliente.png" class="d-block w-100" style="max-height: 500px; object-fit: cover;" alt="Faça Parte da Nossa Equipe">
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div> 
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInicialSC"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselInicialSC"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>
</div>
<div class="container text-center my-5 py-5">
    <h1 class="display-3 fw-bold">Bem-vindo(a) à <strong>StarClean</strong></h1>
    <p class="lead col-lg-8 mx-auto">A sua plataforma para agendar serviços de limpeza com qualidade e confiança.</p>
</div>

<?php include 'includes/footer.php'; ?>