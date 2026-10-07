<?php
$pageTitle = 'About Us | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
?>

<style>
    .about-wrapper {
        padding: 24px 0 80px;
    }

    .hero-section {
        position: relative;
        overflow: hidden;
        padding: 80px 5%;
        text-align: center;
    }

    .hero-title {
        position: relative;
        margin-bottom: 1.2rem;
        color: #ffffff;
        font-size: clamp(2.8rem, 5vw, 4rem);
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .hero-title::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        width: 120px;
        height: 4px;
        background: linear-gradient(90deg, transparent, #ff6200, #ff8a1f, #ff6200, transparent);
        border-radius: 2px;
        box-shadow: 0 2px 12px rgba(255, 98, 0, 0.6);
        transform: translateX(-50%);
    }

    .hero-subtitle {
        max-width: 800px;
        margin: 0 auto 30px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.3rem;
        line-height: 1.8;
    }

    .section {
        position: relative;
        padding: 90px 5%;
    }

    .section-title {
        margin-bottom: 50px;
        text-align: center;
    }

    .section-title h2 {
        position: relative;
        display: inline-block;
        color: #ffffff;
        font-size: clamp(2rem, 4vw, 3rem);
    }

    .section-title h2::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        width: 80px;
        height: 3px;
        background: #ff6200;
        border-radius: 2px;
        transform: translateX(-50%);
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .glass-card {
        position: relative;
        overflow: hidden;
        padding: 32px;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 24px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transition: all 0.4s ease;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .glass-card:hover {
        background: rgba(255, 255, 255, 0.11);
        border-color: rgba(255, 98, 0, 0.3);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 98, 0, 0.15);
        transform: translateY(-6px);
    }

    .glass-card:hover::before {
        left: 100%;
    }

    .icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 90px;
        height: 90px;
        margin-bottom: 20px;
        background: linear-gradient(135deg, rgba(255, 98, 0, 0.2), rgba(255, 138, 31, 0.15));
        border-radius: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.4s ease;
    }

    .icon-container img {
        width: 50px;
        height: 50px;
        filter: brightness(0) invert(1);
    }

    .glass-card:hover .icon-container {
        box-shadow: 0 8px 24px rgba(255, 98, 0, 0.25);
        transform: scale(1.05);
    }

    .glass-card h3 {
        margin-bottom: 12px;
        color: #ffffff;
        font-size: 1.6rem;
        font-weight: 700;
    }

    .glass-card p {
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.8;
    }

    .stats-row {
        display: flex;
        justify-content: space-around;
        max-width: 1200px;
        margin: 60px auto;
        padding: 40px 20px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 20px;
        backdrop-filter: blur(20px);
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        display: block;
        margin-bottom: 8px;
        color: #ff6200;
        font-size: 3rem;
        font-weight: 800;
        transition: transform 0.4s ease;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .stat-item:hover .stat-number {
        transform: scale(1.1);
    }

    .team-section {
        text-align: center;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        max-width: 1200px;
        margin: 40px auto 0;
    }

    .team-card {
        position: relative;
        overflow: hidden;
        padding: 28px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 24px;
        backdrop-filter: blur(16px);
        transition: all 0.4s ease;
    }

    .team-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff6200, #ff8a1f);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .team-card:hover {
        border-color: rgba(255, 98, 0, 0.3);
        box-shadow: 0 24px 50px rgba(0, 0, 0, 0.3);
        transform: translateY(-6px);
    }

    .team-card:hover::before {
        transform: scaleX(1);
    }

    .team-name {
        margin-bottom: 8px;
        color: #ffffff;
        font-size: 1.4rem;
        font-weight: 700;
    }

    .team-role {
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .stats-row {
            flex-direction: column;
            gap: 20px;
        }

        .hero-section {
            padding: 60px 4%;
        }

        .section {
            padding: 70px 4%;
        }
    }
</style>

<div class="about-wrapper">
    <section class="hero-section">
        <h1 class="hero-title">About CoolAir HVAC</h1>

        <p class="hero-subtitle">
            Well-established company known for excellent track record and best customer satisfaction.
            Expertise in HVAC, Electrical works, and Structure fabrication across industries.
        </p>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>Our Core Expertise</h2>
        </div>

        <div class="cards-grid">
            <div class="glass-card">
                <div class="icon-container">
                    <img src="../img/icons/hvac.png" alt="HVAC">
                </div>
                <h3>HVAC Solutions</h3>
                <p>
                    Complete HVAC systems for food industry, IT infrastructure, data centers,
                    pharmaceuticals, hospitals. Design, supply, installation &amp; commissioning.
                </p>
            </div>

            <div class="glass-card">
                <div class="icon-container">
                    <img src="../img/icons/electrical.png" alt="Electrical">
                </div>
                <h3>Electrical Works</h3>
                <p>
                    Qualified engineers with 15+ years experience handling electrical systems
                    supporting HVAC projects across India.
                </p>
            </div>

            <div class="glass-card">
                <div class="icon-container">
                    <img src="../img/icons/structure.png" alt="Structure fabrication">
                </div>
                <h3>Structure Fabrication</h3>
                <p>
                    Heavy/light structures, pre-engineered buildings, roof/wall cladding,
                    metal art with certified welders &amp; skilled labour.
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="stats-row">
            <div class="stat-item">
                <span class="stat-number">15+</span>
                <span class="stat-label">Years Experience</span>
            </div>

            <div class="stat-item">
                <span class="stat-number">50+</span>
                <span class="stat-label">Team Strength</span>
            </div>

            <div class="stat-item">
                <span class="stat-number">20+</span>
                <span class="stat-label">Industries Served</span>
            </div>
        </div>
    </section>

    <section class="section team-section">
        <div class="section-title">
            <h2>Management Team</h2>
        </div>

        <div class="team-grid">
            <div class="team-card">
                <div class="team-name">Nitin Upadhayay</div>
                <div class="team-role">Director &amp; Management Head</div>
            </div>

            <div class="team-card">
                <div class="team-name">Abhishek Thapa</div>
                <div class="team-role">Company Management</div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>