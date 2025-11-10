<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Excise Department | Official Portal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        :root {
            --bg: #070812;
            --card-bg: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.06);
            --accent-1: #3beaef;
            --accent-2: #4b60ff;
            --accent-3: #ccf381;
            --muted: #d7d7d7;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #04050a 0, #0b0e1a 60%);
            color: #fff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Layout */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            position: relative;
            overflow: hidden;
            background: url('assets/images/entery_page_image/excise-dep.JPG') no-repeat center center/cover;
        }

        /* Add black overlay on background image */
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 0;
        }

        /* Main card (glass) */
        .entry-card {
            position: relative;
            z-index: 2;
            max-width: 1100px;
            width: 100%;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.02));
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 40px rgba(2, 6, 23, 0.6);
            overflow: hidden;
        }

        .entry-grid {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            min-height: 420px;
            align-items: stretch;
        }

        /* left: image */
        .entry-visual {
            position: relative;
            overflow: hidden;
            min-height: 400px;
        }

        .entry-visual img {
            width: 100%;
            height: 100%;
            /* object-fit: cover; */
            display: block;
            transition: transform 7s ease;
        }

        .entry-visual:hover img {
            transform: scale(1.06);
        }

        /* .entry-visual::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(3, 8, 18, 0.65) 25%, rgba(3, 8, 18, 0.12) 60%, transparent 100%);
        } */

        /* right: content */
        .entry-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
            background: rgb(0 0 0 / 15%) !important;
            backdrop-filter: blur(60px) saturate(160%);
        }

        .dept-header {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .dept-logo {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            padding: 10px;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(75, 96, 255, 0.18);
        }

        .dept-logo img {
            width: 100%;
            height: auto;
            display: block;
            filter: brightness(1.1) saturate(1.05);
        }

        .dept-title h1 {
            font-size: 1.4rem;
            margin: 0;
            line-height: 1.5;
            font-weight: 700;
            background: linear-gradient(90deg, var(--accent-1), var(--accent-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dept-title p {
            margin: 0;
            color: var(--muted);
            font-size: 0.92rem;
            margin-top: 4px;
        }

        .disclaimers {
            margin-top: 8px;
            display: grid;
            gap: 10px;
        }

        .disc-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 8px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.02);
        }

        .disc-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            flex: 0 0 36px;
            background: linear-gradient(180deg, var(--accent-2), var(--accent-1));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 6px 16px rgba(59, 174, 239, 0.12);
        }

        .disc-text {
            color: #e8e8e8;
            font-size: 13px;
            line-height: 1.5;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.04), transparent);
            margin: 8px 0;
            border-radius: 2px;
        }

        .cta-row {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .btn-cta {
            border-radius: 20px;
            font-weight: 600;
            padding: 0.6rem 2rem;
            font-size: 15px;
            background: #7386ff;
            border: none;
            color: #fff;
        }

        .btn-cta:hover {
            background: #4b59ba;
            color: #fff;
        }

        .muted-small {
            color: var(--muted);
            font-size: 0.85rem;
        }

        @media (max-width: 992px) {
            .entry-grid {
                grid-template-columns: 1fr;
            }

            .entry-visual {
                min-height: 260px;
            }

            .entry-body {
                padding: 22px;
            }

            .dept-title h1 {
                font-size: 1.35rem;
            }
        }
    </style>
</head>

<body>

    <main class="hero">
        <section class="entry-card">
            <div class="entry-grid">
                <div class="entry-visual">
                    <img src="{{ asset('assets/images/entery_page_image/tax-stop.jpeg') }}"
                        alt="Excise Department background image" loading="lazy">
                </div>

                <div class="entry-body">
                    <div>
                        <div class="dept-header">
                            <div class="dept-logo" aria-hidden="true">
                                <img src="{{ asset('assets/images/banner/haryana-logo.png') }}" alt="Haryana logo">
                            </div>
                            <div class="dept-title">
                                <h1 class="m-0">कर हितैषी</h1>
                                <p class="m-0">Excise &amp; Taxation Department, Government of Haryana</p>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="p-2 rounded-4 shadow-lg"
                            style="background: linear-gradient(135deg, #153462, #1A4D8C); color: #f1f6ff; border-left: 6px solid #00c4ff;  font-size: 12px;">



                            <h5 class="fw-semibold text-warning mb-2">Disclaimer</h5>
                            <ul style="list-style-type: square; padding-left: 1.5rem; line-height: 1.7;">
                                <li>This app pertains to providing information related to evasion of <strong>GST / VAT /
                                        CST
                                        / Excise</strong> only. No other information should be given.</li>
                                <li>It shall be noted that providing information does not entitle the informer to any
                                    award.
                                </li>
                                <li>The name of the informer will be kept secret.</li>
                            </ul>

                            <hr style="border-top: 1px dashed rgba(255,255,255,0.3); margin: 10px;">

                            <h5 class="fw-semibold text-warning mb-2">अस्वीकरण</h5>
                            <ul style="list-style-type: square; padding-left: 1.5rem; line-height: 1.7;">
                                <li>यह ऐप केवल <strong>जीएसटी / वैट / सीएसटी / उत्पाद शुल्क</strong> की चोरी से संबंधित
                                    जानकारी प्रदान करने के लिए है। कोई अन्य जानकारी नहीं दी जानी चाहिए।</li>
                                <li>कृपया ध्यान दें कि जानकारी प्रदान करने से सूचना देने वाले को कोई पुरस्कार नहीं
                                    मिलेगा।
                                </li>
                                <li>सूचना देने वाले का नाम गुप्त रखा जाएगा।</li>
                            </ul>
                        </div>
                    </div>

                    <div class="">
                        <div class="cta-row">
                            <a href="{{ route('login') }}" class="btn btn-cta btn-lg" role="button">Register /
                                Login</a>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
