<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <h1 class="display-4 fw-bold text-white mb-3">ProShine Algarve</h1>
        <p class="lead text-white mb-4">Polimentos</p>
        <p class="lead text-white mb-4">Aplicação de PPF</p>
        <p class="lead text-white mb-4">Detalhe automóvel</p>
        <p class="lead text-white mb-4">Produtos profissionais para limpeza</p>
        <a href="?a=loja" class="btn btn-primary btn-lg">Ver Produtos</a>
    </div>
</div>

<!-- Categorias Section -->
<div class="container py-5">
    <h2 class="text-center mb-5">Nossas Categorias</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-spray-can fa-3x"></i>
                </div>
                <h3>Limpeza Externa</h3>
                <p>Shampoos, ceras, desengordurantes e mais</p>
                <a href="?a=loja" class="btn btn-outline-primary">Ver Produtos</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-car-side fa-3x"></i>
                </div>
                <h3>Detalhe automóvel</h3>
                <p>Polimentos, abrasivos e produtos para acabamento</p>
                <a href="?a=loja" class="btn btn-outline-primary">Ver Produtos</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="category-card">
                <div class="category-icon">
                <i class="fas fa-spray-can fa-3x"></i>
                </div>
                <h3>Limpeza Interna</h3>
                <p>Produtos para interior, couro e plásticos</p>
                <a href="?a=loja" class="btn btn-outline-primary">Ver Produtos</a>
            </div>
        </div>
    </div>
</div>

<!-- Benefícios Section -->
<div class="benefits-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="benefit-card">
                    <i class="fa-solid fa-map-location-dot fa-2x mb-3"></i>
                    <h5>Localização</h5>
                    <p>R. do Pinheiro 71 73, 8125-256 Quarteira</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="benefit-card">
                    <i class="fas fa-medal fa-2x mb-3"></i>
                    <h5>Qualidade Premium</h5>
                    <p>Produtos de primeira linha</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="benefit-card">
                <i class="fa-solid fa-phone fa-2x mb-3"></i>
                    <h5>Contacto</h5>
                    <p>+351 962 986 650</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="benefit-card">
                    <i class="fas fa-shield-alt fa-2x mb-3"></i>
                    <h5>Diversidade de Serviços</h5>
                    <p>Variedade de tipos de serviços</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/images/redrari.jpg');
    background-size: cover;
    background-position: center;
    height: 60vh;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.hero-content {
    max-width: 800px;
    padding: 0 2rem;
    margin: 0;
}

/* Category Cards */
.category-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-10px);
}

.category-icon {
    color: #2c3e50;
    margin-bottom: 1rem;
}

/* Featured Products */
.featured-products {
    background: #f8f9fa;
}

.product-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-content {
    padding: 1rem;
    text-align: center;
}

.price {
    color: #2c3e50;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 0.5rem 0;
}

/* Benefits Section */
.benefits-section {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    color: white;
    margin: 0;
    padding: 3rem 0;
    margin-bottom: 2rem;
    width: 100%;
    overflow: hidden;
}

.benefit-card {
    text-align: center;
    padding: 1.5rem;
}

.benefit-card i {
    color: #3498db;
}

/* Buttons */
.btn-primary {
    background: #2c3e50;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #3498db;
    transform: translateY(-2px);
}

.btn-outline-primary {
    border: 2px solid #2c3e50;
    color: #2c3e50;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #2c3e50;
    color: white;
    transform: translateY(-2px);
}

/* Responsividade */
@media (max-width: 768px) {
    .hero-section {
        height: 400px;
    }
    
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .product-image {
        height: 150px;
    }
}

/* Remover espaços laterais extras */
.container {
    max-width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

@media (min-width: 1200px) {
    .container {
        max-width: 1140px;
    }
}
</style>