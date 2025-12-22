<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Excise Department | Official Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

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

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            position: relative;
            overflow: hidden;
            background: url('assets/images/entery_page_image/banner-1.png') no-repeat center center/cover;
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 0;
        }

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

        .entry-visual {
            position: relative;
            overflow: hidden;
            min-height: 400px;
        }

        .entry-visual img {
            width: 100%;
            height: 100%;
            display: block;
            transition: transform 7s ease;
        }

        .entry-visual:hover img {
            transform: scale(1.06);
        }

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
            color: #FF9E3E;
            /* background: linear-gradient(90deg, var(--accent-1), var(--accent-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent; */
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
                    <img src="{{ asset('assets/images/entery_page_image/excise-dep.jpeg') }}"
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
                                <li>This app pertains only for providing information related to evasion of
                                    {{-- GST/ VAT/ CST/ --}}
                                    GST.</li>
                                <li>The details of the informer will be kept masked.
                                </li>
                                {{-- <li>The informer must verify that the information being provided is correct to his
                                    best
                                    of knowledge and it is being provided only with the purpose to curb tax evasion.
                                </li> --}}
                            </ul>

                            <hr style="border-top: 1px dashed rgba(255,255,255,0.3); margin: 10px;">

                            <h5 class="fw-semibold text-warning mb-2">औपचारिक बयान</h5>
                            <ul style="list-style-type: square; padding-left: 1.5rem; line-height: 1.7;">
                                <li>यह ऐप केवल जीएसटी शुल्क की चोरी से संबंधित जानकारी प्रदान करने के
                                    लिए है।</li>
                                <li>सूचना देने वाले की जानकारी गोपनीय रखी जाएगी।</li>
                                {{-- <li>सूचना देने वाले को यह सत्यापित करना होगा कि दी जा रही जानकारी उनकी सर्वोत्तम
                                    जानकारी
                                    के अनुसार सही है और यह केवल कर चोरी को रोकने के उद्देश्य से प्रदान की जा रही है।
                                </li> --}}
                            </ul>
                        </div>
                    </div>

                    <div class="">
                        <div class="cta-row">
                            <a href="{{ route('login') }}" class="btn btn-cta btn-lg" role="button">Register /
                                Login</a>

                        </div>
                        {{-- KarHitesh.apk --}}
                        {{-- <a href="{{ asset('KarHitesh.apk') }}" class="btn btn-primary" download>
                            Download Karheteshi App
                        </a> --}}
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('status'))
    <script>
        Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('status') }}",
                confirmButtonText: 'OK'
            });
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>