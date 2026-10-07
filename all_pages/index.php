<?php
$pageTitle = 'CoolAir HVAC | Comfort You Can Trust';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
?>

<section class="hero">
    <video autoplay muted loop playsinline>
        <source src="../vid/background-video.mp4" type="video/mp4">
    </video>

    <div class="hero-content">
        <?php if ($isLoggedIn): ?>
            <p class="hero-welcome">
                Welcome back,
                <span><?= htmlspecialchars($_SESSION['name'] ?? $_SESSION['full_name'] ?? 'User') ?>!</span>
            </p>
        <?php else: ?>
            <p class="hero-welcome">
                Welcome to <span>CoolAir HVAC</span>
            </p>
        <?php endif; ?>

        <h1><?= htmlspecialchars($companyName) ?></h1>
        <p><?= htmlspecialchars($tagline) ?></p>

        <?php if ($isAdmin): ?>
            <a href="../login/requests.php" class="btn">Requests</a>
            <a href="../login/complaint.php" class="btn">Complaint</a>
        <?php else: ?>
            <a href="about.php" class="btn">About Us</a>
            <a href="services.php" class="btn">Our Services</a>
            <a href="contact.php" class="btn">Contact</a>
            <a href="schedule.php" class="btn">Schedule</a>
        <?php endif; ?>
    </div>
</section>

<section class="clients">
    <h2>Companies that work with us</h2>

    <div class="ribbon-container">
        <a href="clients.php" aria-label="View our clients">
            <div class="logo-track">
                <?php
                $companyLogos = [
                    ['myntra.png', 'Myntra'],
                    ['suzuki.png', 'Suzuki'],
                    ['oppo.png', 'Oppo'],
                    ['vivo.png', 'Vivo'],
                    ['airtel.png', 'Airtel'],
                    ['foxconn.png', 'Foxconn'],
                    ['byd.png', 'BYD'],
                    ['honda.png', 'Honda'],
                ];

                foreach (array_merge($companyLogos, $companyLogos) as [$file, $name]):
                ?>
                    <img src="../img/companies/<?= htmlspecialchars($file) ?>" alt="<?= htmlspecialchars($name) ?>">
                <?php endforeach; ?>
            </div>
        </a>
    </div>
</section>

<section class="why-choose">
    <h2>Why Choose Us?</h2>

    <div class="why-wrap">
        <div class="why-left">
            <div class="boxes">
                <div class="box">
                    <img src="../img/icons/money-bag.png" alt="Pricing">
                    <h3>Competitive Pricing</h3>
                    <p>Unbeatable rates with transparent quotes. Best value HVAC solutions.</p>
                </div>

                <div class="box">
                    <img src="../img/icons/technician.png" alt="Technician">
                    <h3>Certified Technicians</h3>
                    <p>Factory-trained experts with 10+ years experience across industries.</p>
                </div>

                <div class="box">
                    <img src="../img/icons/checkmark.png" alt="Satisfaction">
                    <h3>100% Satisfaction</h3>
                    <p>Job not right? We make it perfect. Your trust is our priority.</p>
                </div>

                <div class="box">
                    <img src="../img/icons/clock.png" alt="Response time">
                    <h3>Rapid Response</h3>
                    <p>Emergency calls answered within 1 hour. 24/7 service ready.</p>
                </div>
            </div>

            <div class="trust-line">
                <span>IT'S SIMPLE: WE DELIVER TRUST</span>
            </div>
        </div>

        <div class="why-right">
            <div class="why-image"></div>
        </div>
    </div>
</section>

<style>
    .hero {
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 80px 10% 0;
    }

    .hero video {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -2;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(6, 19, 31, 0.82), rgba(6, 19, 31, 0.35));
        z-index: -1;
    }

    .hero-content h1 {
        margin-bottom: 1rem;
        color: #ffffff;
        font-size: 4rem;
        font-weight: bold;
        text-align: left;
        text-shadow: 0 6px 25px rgba(0, 0, 0, 0.45);
    }

    .hero-content p {
        max-width: 600px;
        margin-bottom: 2rem;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.4rem;
        text-align: left;
    }

    .hero-welcome {
        margin-bottom: 0.6rem !important;
        color: rgba(255, 255, 255, 0.86) !important;
        font-size: 1.1rem !important;
        font-weight: 600;
    }

    .hero-welcome span {
        color: #ff9a4d;
    }

    .btn {
        display: inline-block;
        margin: 0 1rem 1rem 0;
        padding: 15px 30px;
        color: #ffffff;
        text-decoration: none;
        font-weight: 600;
        border-radius: 50px;
        background: linear-gradient(135deg, #ff6200, #ff8a1f);
        box-shadow: 0 12px 30px rgba(255, 98, 0, 0.28);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 16px 34px rgba(255, 98, 0, 0.36);
    }

    .clients {
        position: relative;
        overflow: hidden;
        padding: 80px 5%;
        background: rgba(255, 255, 255, 0.02);
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .clients::before,
    .clients::after {
        content: '';
        position: absolute;
        top: 0;
        z-index: 2;
        width: 90px;
        height: 100%;
        pointer-events: none;
    }

    .clients::before {
        left: 0;
        background: linear-gradient(90deg, rgba(6, 19, 31, 1), rgba(6, 19, 31, 0));
    }

    .clients::after {
        right: 0;
        background: linear-gradient(270deg, rgba(6, 19, 31, 1), rgba(6, 19, 31, 0));
    }

    .clients h2,
    .why-choose h2 {
        max-width: 1200px;
        margin: 0 auto 2rem;
        color: #ffffff;
        font-size: 2.5rem;
        text-align: left;
    }

    .ribbon-container {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        overflow: hidden;
        cursor: pointer;
        border-radius: 22px;
        -webkit-mask-image: linear-gradient(to right, transparent 0%, #000 8%, #000 92%, transparent 100%);
        mask-image: linear-gradient(to right, transparent 0%, #000 8%, #000 92%, transparent 100%);
    }

    .ribbon-container a {
        display: block;
        text-decoration: none;
    }

    .logo-track {
        position: relative;
        display: flex;
        gap: 3rem;
        width: max-content;
        padding: 20px 0;
        background: linear-gradient(90deg, rgba(255, 98, 0, 0.92), rgba(255, 138, 31, 0.84));
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
        animation: scroll 28s linear infinite;
        will-change: transform;
    }

    .logo-track::before {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        border-radius: 18px;
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0.12),
            transparent 30%,
            transparent 70%,
            rgba(255, 255, 255, 0.08)
        );
    }

    .logo-track img {
        width: auto;
        height: 50px;
        object-fit: contain;
        filter: brightness(0) invert(1);
        opacity: 0.95;
        transition: transform 0.25s ease, opacity 0.25s ease;
    }

    .logo-track img[alt="Foxconn"] {
        height: 30px;
        transform: scale(0.88);
    }

    .logo-track img[alt="Honda"],
    .logo-track img[alt="BYD"],
    .logo-track img[alt="Oppo"],
    .logo-track img[alt="Vivo"] {
        height: 56px;
        transform: scale(1.08);
    }

    .logo-track:hover {
        animation-play-state: paused;
    }

    .logo-track:hover img {
        opacity: 1;
        transform: scale(1.05);
    }

    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .why-choose {
        padding: 100px 5%;
        background: rgba(255, 255, 255, 0.03);
    }

    .why-wrap {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        align-items: stretch;
    }

    .why-left {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .boxes {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .box {
        padding: 2rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 18px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.22);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        animation: boxFloat 4s ease-in-out infinite, boxGlow 3.5s ease-in-out infinite;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .box:hover {
        transform: translateY(-8px);
        border-color: rgba(255, 255, 255, 0.22);
        box-shadow:
            0 0 0 1px rgba(255, 98, 0, 0.18),
            0 18px 42px rgba(255, 98, 0, 0.12),
            0 20px 40px rgba(0, 0, 0, 0.28);
    }

    .box:nth-child(2) {
        animation-delay: 0.5s, 0.5s;
    }

    .box:nth-child(3) {
        animation-delay: 1s, 1s;
    }

    .box:nth-child(4) {
        animation-delay: 1.5s, 1.5s;
    }

    @keyframes boxFloat {
        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-6px);
        }
    }

    @keyframes boxGlow {
        0%,
        100% {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.22);
        }

        50% {
            box-shadow:
                0 22px 48px rgba(255, 98, 0, 0.08),
                0 20px 40px rgba(0, 0, 0, 0.22);
        }
    }

    .box img {
        width: 80px;
        height: 80px;
        margin-bottom: 1rem;
        object-fit: contain;
    }

    .box h3 {
        margin-bottom: 1rem;
        color: #ffffff;
    }

    .box p {
        color: rgba(255, 255, 255, 0.78);
    }

    .why-image {
        width: 100%;
        min-height: 520px;
        height: 100%;
        background-image: url('../img/why-choose-us.jpg');
        background-position: center;
        background-size: cover;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 22px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    }

    .trust-line {
        position: relative;
        width: fit-content;
        margin-top: 2rem;
        margin-left: auto;
        overflow: hidden;
        color: #ffffff;
        font-size: clamp(1.2rem, 2.4vw, 2rem);
        font-weight: bold;
        text-align: right;
        white-space: nowrap;
    }

    .trust-line span {
        position: relative;
        display: inline-block;
        color: rgba(255, 255, 255, 0.95);
    }

    .trust-line span::after {
        content: '';
        position: absolute;
        top: 0;
        left: -140%;
        width: 60%;
        height: 100%;
        pointer-events: none;
        background: linear-gradient(
            90deg,
            transparent 0%,
            rgba(255, 255, 255, 0.15) 35%,
            rgba(255, 255, 255, 1) 50%,
            rgba(255, 255, 255, 0.15) 65%,
            transparent 100%
        );
        transform: skewX(-20deg);
        mix-blend-mode: screen;
        animation: shineSweep 3.2s ease-in-out infinite;
    }

    @keyframes shineSweep {
        0% {
            left: -140%;
            opacity: 0;
        }

        18% {
            opacity: 1;
        }

        45% {
            left: 140%;
            opacity: 1;
        }

        70%,
        100% {
            left: 140%;
            opacity: 0;
        }
    }

    @media (max-width: 1100px) {
        .why-wrap {
            grid-template-columns: 1fr;
        }

        .why-image {
            min-height: 360px;
        }
    }

    @media (max-width: 900px) {
        .hero-content h1 {
            font-size: 3rem;
        }

        .hero-content p {
            font-size: 1.2rem;
        }

        .trust-line {
            font-size: clamp(1.05rem, 2.2vw, 1.5rem);
        }
    }

    @media (max-width: 768px) {
        .hero {
            justify-content: center;
            padding: 80px 5% 0;
            text-align: center;
        }

        .hero-content {
            width: 100%;
        }

        .hero-content h1,
        .hero-content p {
            text-align: center;
        }

        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-content p {
            max-width: 100%;
            font-size: 1rem;
        }

        .btn {
            width: 100%;
            margin-right: 0;
            text-align: center;
        }

        .clients h2,
        .why-choose h2 {
            font-size: 2rem;
        }

        .boxes {
            grid-template-columns: 1fr;
        }

        .why-image {
            min-height: 300px;
        }

        .trust-line {
            width: 100%;
            max-width: 100%;
            margin-left: 0;
            font-size: 1.15rem;
            text-align: center;
            white-space: normal;
        }

        .logo-track {
            gap: 2rem;
            padding: 16px 0;
            animation-duration: 32s;
        }

        .logo-track img {
            height: 40px;
        }

        .logo-track img[alt="Foxconn"] {
            height: 24px;
            transform: scale(0.78);
        }

        .logo-track img[alt="Honda"],
        .logo-track img[alt="BYD"],
        .logo-track img[alt="Oppo"],
        .logo-track img[alt="Vivo"] {
            height: 44px;
            transform: scale(1.05);
        }

        .clients::before,
        .clients::after {
            width: 40px;
        }

        .ribbon-container {
            -webkit-mask-image: linear-gradient(to right, transparent 0%, #000 12%, #000 88%, transparent 100%);
            mask-image: linear-gradient(to right, transparent 0%, #000 12%, #000 88%, transparent 100%);
        }
    }

    @media (max-width: 480px) {
        .hero-content h1 {
            font-size: 2rem;
        }

        .clients {
            padding: 60px 4%;
        }

        .why-choose {
            padding: 70px 4%;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>