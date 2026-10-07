<?php
$pageTitle = 'Our Services | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
?>

<style>
    .service-wrapper {
        padding: 24px 0 80px;
    }

    .service-hero {
        position: relative;
        overflow: hidden;
        padding: 90px 5% 70px;
        text-align: center;
    }

    .service-hero h1 {
        position: relative;
        margin-bottom: 1rem;
        color: #ffffff;
        font-size: clamp(2.8rem, 5vw, 4.3rem);
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .service-hero h1::after {
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

    .service-hero p {
        max-width: 850px;
        margin: 25px auto 0;
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.25rem;
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

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .service-card {
        position: relative;
        overflow: hidden;
        padding: 32px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 24px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transition: all 0.4s ease;
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .service-card:hover {
        background: rgba(255, 255, 255, 0.11);
        border-color: rgba(255, 98, 0, 0.3);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 98, 0, 0.15);
        transform: translateY(-6px);
    }

    .service-card:hover::before {
        left: 100%;
    }

    .icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 88px;
        height: 88px;
        margin-bottom: 20px;
        background: linear-gradient(135deg, rgba(255, 98, 0, 0.2), rgba(255, 138, 31, 0.15));
        border-radius: 20px;
        backdrop-filter: blur(10px);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .service-card:hover .icon-box {
        box-shadow: 0 8px 24px rgba(255, 98, 0, 0.25);
        transform: scale(1.05);
    }

    .icon-box img {
        width: 48px;
        height: 48px;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }

    .service-card h3 {
        margin-bottom: 12px;
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .service-card p {
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.8;
    }

    .details-wrap {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 24px;
        align-items: stretch;
        max-width: 1200px;
        margin: 0 auto;
    }

    .detail-card {
        padding: 32px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.22);
        backdrop-filter: blur(16px);
    }

    .detail-card h3 {
        margin-bottom: 16px;
        color: #ffffff;
        font-size: 1.8rem;
    }

    .detail-card p,
    .detail-card li {
        margin-bottom: 10px;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.8;
    }

    .detail-card ul {
        padding-left: 18px;
    }

    .cta-box {
        max-width: 1200px;
        margin: 0 auto;
        padding: 36px;
        text-align: center;
        background: linear-gradient(135deg, rgba(255, 98, 0, 0.18), rgba(255, 138, 31, 0.08));
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 26px;
        box-shadow: 0 22px 50px rgba(0, 0, 0, 0.22);
        backdrop-filter: blur(16px);
    }

    .cta-box h2 {
        margin-bottom: 15px;
        color: #ffffff;
        font-size: clamp(2rem, 4vw, 2.8rem);
    }

    .cta-box p {
        max-width: 900px;
        margin: 0 auto 24px;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.8;
    }

    .cta-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
    }

    .cta-btn {
        display: inline-block;
        padding: 14px 28px;
        color: #ffffff;
        text-decoration: none;
        font-weight: 600;
        background: linear-gradient(135deg, #ff6200, #ff8a1f);
        border-radius: 50px;
        box-shadow: 0 12px 30px rgba(255, 98, 0, 0.28);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .cta-btn:hover {
        color: #ffffff;
        box-shadow: 0 16px 34px rgba(255, 98, 0, 0.36);
        transform: translateY(-2px);
    }

    .cta-btn.secondary {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: none;
    }

    @media (max-width: 900px) {
        .details-wrap {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .service-hero {
            padding: 70px 4% 50px;
        }

        .service-hero p {
            font-size: 1.05rem;
        }

        .section {
            padding: 70px 4%;
        }

        .services-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="service-wrapper">
    <section class="service-hero">
        <h1>Our Services</h1>

        <p>
            CoolAir HVAC delivers complete HVAC solutions for industrial, commercial,
            and specialized applications, including chiller systems, AHU and MAU connections,
            plant room installations, cooling towers, VRF systems, and end-to-end execution.
        </p>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>What We Offer</h2>
        </div>

        <div class="services-grid">
            <?php
            $services = [
                [
                    'icon' => 'hvac.png',
                    'alt' => 'HVAC',
                    'title' => 'Complete HVAC Systems',
                    'description' => 'We handle heating, ventilation, and air conditioning systems from design to installation, testing, and commissioning for industrial and commercial projects.',
                ],
                [
                    'icon' => 'chiller.png',
                    'alt' => 'Chiller',
                    'title' => 'Chiller Installation',
                    'description' => 'We install all kinds of air-cooled and water-cooled chillers, including scroll and screw compressor systems, with proper alignment, piping, and performance setup.',
                ],
                [
                    'icon' => 'fan.png',
                    'alt' => 'AHU',
                    'title' => 'AHU Installation',
                    'description' => 'Air Handling Unit installation and connections are carried out with precision for efficient air distribution, better indoor quality, and reliable system performance.',
                ],
                [
                    'icon' => 'ventilation.png',
                    'alt' => 'MAU',
                    'title' => 'MAU Installation',
                    'description' => 'Make Up Air Unit installation and integration support fresh air supply systems, especially where controlled ventilation and industrial air balance are required.',
                ],
                [
                    'icon' => 'machine.png',
                    'alt' => 'Plant room',
                    'title' => 'Plant Room Works',
                    'description' => 'We execute complete plant room installations with proper equipment placement, interconnections, piping layout, and service-friendly arrangement.',
                ],
                [
                    'icon' => 'pump.png',
                    'alt' => 'Pump room',
                    'title' => 'Pump Room Installation',
                    'description' => 'Pump room setup includes energy-efficient pumps, correct connections, and a clean installation flow that supports long-term HVAC operation.',
                ],
                [
                    'icon' => 'cooling.png',
                    'alt' => 'Cooling tower',
                    'title' => 'Cooling Tower Systems',
                    'description' => 'We install cooling towers for chilled water systems with proper water circulation, support, and integration with central plant operations.',
                ],
                [
                    'icon' => 'air-conditioning.png',
                    'alt' => 'VRF system',
                    'title' => 'VRF / VRV Systems',
                    'description' => 'Digital and inverter refrigerant flow systems are installed for efficient, flexible, and high-performance climate control.',
                ],
                [
                    'icon' => 'technician-white.png',
                    'alt' => 'Industrial HVAC',
                    'title' => 'Industrial HVAC Experts',
                    'description' => 'We serve food industry, data centers, electronic plants, hospitals, pharma units, and commercial projects with reliable industrial HVAC execution.',
                ],
                [
                    'icon' => 'dehumidifier.png',
                    'alt' => 'Process equipment',
                    'title' => 'Process Equipment',
                    'description' => 'Specialized dehumidifier systems, air purifiers, and process equipment are provided for controlled environments and demanding technical applications.',
                ],
                [
                    'icon' => 'pipe.png',
                    'alt' => 'Pipeline connections',
                    'title' => 'Complete Pipeline Connections',
                    'description' => 'We provide chiller, boiler, dryer, AHU, FCU, cassette unit, and complete piping connection services with clean execution and proper coordination.',
                ],
                [
                    'icon' => 'planning.png',
                    'alt' => 'Consultancy',
                    'title' => 'HVAC Consultancy',
                    'description' => 'Our consultancy support helps clients plan, design, and execute HVAC work with practical recommendations aligned with project needs.',
                ],
            ];

            foreach ($services as $service):
            ?>
                <div class="service-card">
                    <div class="icon-box">
                        <img
                            src="../img/icons/<?= htmlspecialchars($service['icon']) ?>"
                            alt="<?= htmlspecialchars($service['alt']) ?>"
                        >
                    </div>

                    <h3><?= htmlspecialchars($service['title']) ?></h3>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>Service Details</h2>
        </div>

        <div class="details-wrap">
            <div class="detail-card">
                <h3>Central Plant &amp; HVAC Execution</h3>

                <p>
                    We provide complete HVAC execution for central plant systems, including air-cooled
                    and water-cooled chillers, AHUs, FCUs, cooling towers, dehumidifiers, humidifiers,
                    and energy-efficient pumps.
                </p>

                <p>
                    Our team handles every stage carefully — from design and supply to installation,
                    testing, and commissioning — so the system performs efficiently and reliably.
                </p>
            </div>

            <div class="detail-card">
                <h3>Industries We Serve</h3>

                <ul>
                    <li>Food Industry</li>
                    <li>IT Infrastructure and Data Centers</li>
                    <li>Electronic Industry</li>
                    <li>Commercial Projects</li>
                    <li>Pharmaceutical Industry</li>
                    <li>Hospital Applications</li>
                    <li>Other Industrial Applications</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="cta-box">
            <h2>Need a Reliable HVAC Partner?</h2>

            <p>
                From chillers and AHUs to cooling towers, plant rooms, pump rooms, and complete
                industrial HVAC systems, we deliver professional work with attention to quality
                and execution.
            </p>

            <div class="cta-actions">
                <a href="contact.php" class="cta-btn">Contact Us</a>
                <a href="schedule.php" class="cta-btn secondary">Book An Appointment</a>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>