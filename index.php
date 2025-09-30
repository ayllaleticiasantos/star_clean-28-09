<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div>
    <div id="carouselInicialSC" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselInicialSC" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselInicialSC" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselInicialSC" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner h-2">
            <div class="carousel-item active">
                <img src="img/sliderbarprest.jpg " class="d-block w-100 h-100 object-fit-cover" alt="Seja um Prestador de Serviços">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Seja um Prestador de Serviços</h2>
                    <p>Junte-se a nós e ofereça seus serviços de limpeza.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/sliderbarcliente.jpg" class="d-block w-100" alt="Seja um Cliente">
                <div class="carousel-caption d-none d-md-block">
                    <h2 style="color: black;">Seja um dos nossos Clientes</h2>
                    <p style="color: black;">Encontre os melhores prestadores de serviços de limpeza.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/feedbackCliente.png" class="d-block w-100" alt="Feedback de Clientes">
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div> 
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInicialSC"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselInicialSC"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<div class="container text-center mt-5" style="padding: 100px 0 80px;">
    <h1 class="mb-2 display-1">Bem-vindos a <strong>StarClean</strong></h1>
    <p class="lead">A sua plataforma para agendar serviços de limpeza com qualidade e confiança.</p>
</div>

<?php include 'includes/footer.php'; ?>