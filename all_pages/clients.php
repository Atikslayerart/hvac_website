<?php
$pageTitle = 'Our Projects | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';

$projects = [
    [
        'name' => 'Foxconn Chennai',
        'description' => 'Large-scale industrial execution with precision HVAC support for a demanding manufacturing environment.',
    ],
    [
        'name' => 'BYD',
        'description' => 'Industrial HVAC and controlled environment solutions for manufacturing operations.',
    ],
    [
        'name' => 'OPPO',
        'description' => 'Reliable HVAC work for high-performance production and technical facility needs.',
    ],
    [
        'name' => 'Honda India Pvt. Ltd.',
        'description' => 'Industrial cooling and system support for manufacturing-grade infrastructure.',
    ],
    [
        'name' => 'Suzuki Motorcycle',
        'description' => 'HVAC execution for factory environments requiring dependable climate control.',
    ],
    [
        'name' => 'Dixon Technology',
        'description' => 'Technical HVAC solutions for electronics manufacturing and facility operations.',
    ],
    [
        'name' => 'M3M',
        'description' => 'Commercial HVAC solutions for premium building and project requirements.',
    ],
    [
        'name' => 'Delhi Police Headquarters',
        'description' => 'System support and HVAC work for a major government facility.',
    ],
    [
        'name' => 'Haldiram',
        'description' => 'HVAC execution for food industry operations and production spaces.',
    ],
    [
        'name' => 'JK Tyre',
        'description' => 'Industrial cooling and plant-side HVAC services for manufacturing needs.',
    ],
    [
        'name' => 'Techno Mobiles',
        'description' => 'Precise HVAC support for electronics and mobile manufacturing environments.',
    ],
    [
        'name' => 'QTECH',
        'description' => 'Controlled HVAC solutions for technical and industrial workplace conditions.',
    ],
    [
        'name' => 'Greentek Engineers',
        'description' => 'Project-based engineering support with HVAC and installation coordination.',
    ],
    [
        'name' => 'Huake Engineers India Pvt. Ltd.',
        'description' => 'Engineering collaboration for HVAC-related project execution.',
    ],
    [
        'name' => 'Aneomo Projects Pvt. Ltd.',
        'description' => 'Project execution support for HVAC and site work requirements.',
    ],
];
?>

<style>
    .clients-wrapper {
        padding: 24px 0 80px;
    }

    .clients-hero {
        position: relative;
        overflow: hidden;
        padding: 90px 5% 60px;
        text-align: center;
    }

    .clients-hero h1 {
        position: relative;
        margin-bottom: 1rem;
        color: #ffffff;
        font-size: clamp(2.8rem, 5vw, 4.3rem);
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .clients-hero h1::after {
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

    .clients-hero p {
        max-width: 850px;
        margin: 25px auto 0;
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.2rem;
        line-height: 1.8;
    }

    .section {
        position: relative;
        padding: 80px 5%;
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

    .projects-panel {
        max-width: 1100px;
        margin: 0 auto;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 28px;
        box-shadow: 0 18px 50px rgba(0, 0, 0, 0.24);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .projects-scroll {
        max-height: 720px;
        padding: 20px;
        overflow-y: auto;
    }

    .projects-scroll::-webkit-scrollbar {
        width: 10px;
    }

    .projects-scroll::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.06);
    }

    .projects-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #ff6200, #ff8a1f);
        border-radius: 20px;
    }

    .project-row {
        padding: 22px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.10);
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .project-row:last-child {
        border-bottom: 0;
    }

    .project-row:hover {
        background: rgba(255, 255, 255, 0.05);
        transform: translateX(4px);
    }

    .project-name {
        margin-bottom: 8px;
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .project-desc {
        max-width: 900px;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.8;
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
        font-weight: 600;
        text-decoration: none;
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

    @media (max-width: 768px) {
        .clients-hero {
            padding: 70px 4% 40px;
        }

        .clients-hero p {
            font-size: 1.05rem;
        }

        .section {
            padding: 70px 4%;
        }

        .projects-scroll {
            max-height: 640px;
            padding: 14px;
        }

        .project-row {
            padding: 18px 12px;
        }

        .project-name {
            font-size: 1.12rem;
        }
    }
</style>

<div class="clients-wrapper">
    <section class="clients-hero">
        <h1>Our Projects</h1>

        <p>
            CoolAir HVAC has delivered HVAC and electrical solutions across major industrial,
            commercial, and infrastructure projects in India.
        </p>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>Projects Showcase</h2>
        </div>

        <div class="projects-panel">
            <div class="projects-scroll">
                <?php foreach ($projects as $project): ?>
                    <div class="project-row">
                        <div class="project-name">
                            <?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?>
                        </div>

                        <div class="project-desc">
                            <?= htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="cta-box">
            <h2>Trusted Across Industries</h2>

            <p>
                Our project base reflects our experience in industrial HVAC, commercial systems,
                and technical project execution across multiple sectors.
            </p>

            <div class="cta-actions">
                <a href="services.php" class="cta-btn">View Services</a>
                <a href="contact.php" class="cta-btn secondary">Contact Us</a>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>