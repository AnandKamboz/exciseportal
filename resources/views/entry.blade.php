<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Excise Department | Official Portal</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<!-- SweetAlert2 CSS (optional, for default styling) -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      overflow-x: hidden;
      background-color: #0b0e1a;
      color: #fff;
    }

    /* ========== HERO SECTION ========== */
    .hero-section {
      position: relative;
      background: url('./assets/images/excise-dep.JPG') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    /* Dark gradient overlay with slight blue-red tint */
    .hero-overlay {


      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgb(0 0 0 / 64%);
      z-index: 1;






      /* position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: linear-gradient(
        rgba(0, 0, 0, 0.75),
        rgba(15, 15, 35, 0.85)
      ), rgba(0, 0, 0, 0.5);
      z-index: 1; */
    }

    /* Decorative blurred orbs for background effect */
    .decor-circle {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.5;
      z-index: 0;
    }

    .decor-circle.red {
      background: #ef3b3b;
      width: 250px;
      height: 250px;
      top: -80px;
      right: -100px;
    }

    .decor-circle.blue {
      background: #404ee8;
      width: 300px;
      height: 300px;
      bottom: -100px;
      left: -120px;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      width: 90%;
      max-width: 1200px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 40px;
    }

    /* Glassmorphism box for text */
    .hero-text {
      flex: 1 1 60%;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(15px);
      padding: 40px 35px;
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
    }

    .hero-text h1 {
      font-size: 3.2rem;
      line-height: 80px;
      margin-bottom: 0;
      font-weight: 700;
      background: linear-gradient(90deg, #3beaef, #404ee8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-text h3 {
      font-size: 1.2rem;
      color: #CCF381;
      margin-bottom: 15px;
    }

    .hero-text p {
      font-size: 1.1rem;
      color: #dcdcdc;
      line-height: 1.7;
      margin-bottom: 30px;
    }

    .btn-custom {
      background: #879fff;
      border: none;
      color: #fff;
      padding: 12px 35px;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: 0.3s;
      box-shadow: 0 0 15px rgba(239, 59, 59, 0.5);
    }

    .btn-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 0 20px rgba(64, 78, 232, 0.7);
    }

    .hero-image {
      flex: 1 1 30%;
      text-align: center;
    }

    .hero-image img {
      width: 90%;
      max-width: 380px;
      animation: float 4s ease-in-out infinite;
      border-radius: 25px;
      box-shadow: 0 0 35px rgba(255, 255, 255, 0.2);
    }


    .disclaimer-list {
      list-style: none;
      padding-left: 0;
      margin: 0 0 18px 0;
      color: #e9e9e9;
      line-height: 1.6;
      font-size: 0.98rem;
    }

    .disclaimer-list li {
      position: relative;
      padding-left: 28px;
      margin-bottom: 10px;
    }

    .disclaimer-list li::before {
      content: "•";
      position: absolute;
      left: 0;
      top: 0;
      color: #CCF381;
      font-weight: 700;
      font-size: 1.1rem;
      line-height: 1;
    }

    .disclaimer-divider {
      height: 1px;
      background: rgba(255, 255, 255, 0.06);
      margin: 18px 0;
      border-radius: 2px;
    }




    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-10px);
      }
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
      .hero-content {
        flex-direction: column;
        text-align: center;
      }

      .hero-text {
        flex: 1 1 100%;
      }

      .hero-text h1 {
        font-size: 2.3rem;
      }

      .hero-image img {
        margin-top: 30px;
        width: 80%;
      }
    }
  </style>
</head>

<body>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-overlay"></div>

    <!-- Decorative Background Shapes -->
    <div class="decor-circle red"></div>
    <div class="decor-circle blue"></div>

    <div class="hero-content container">
      <div class="hero-text">
        <div class="d-flex ">
    <!-- <img src="./assets/images/haryana-logo.png" alt="Department Logo" class="me-2" style="height:60px; width:auto;"> -->
     <img src="{{ 'assets/images/banner/haryana-logo.png' }}" alt="Department Logo" class="me-2" style="height:60px; width:auto;">

    <h1 class="m-0">कर हितैषी</h1>
  </div>
        <h3> Excise and Taxation Department , Government of Haryana</h3>

        <!-- ENGLISH DISCLAIMER -->
        <div aria-labelledby="disclaimer-en">
          <h3 id="disclaimer-en" style="font-size:1rem;color:#f5f5f5;margin:8px 0 12px 0;font-weight:600;">Disclaimer
          </h3>
          <ul class="disclaimer-list">
            <li>This app pertains to providing information related to evasion of GST/ VAT/ CST/ Excise only. No other
              information should be given.</li>
            <li>It shall be noted that the providing of information does not entitle the informer to any award.</li>
            <li>The name of the informer will be kept secret.</li>
          </ul>
        </div>

        <div class="disclaimer-divider" role="separator" aria-hidden="true"></div>

        <!-- HINDI DISCLAIMER -->
        <div aria-labelledby="disclaimer-hi">
          <h3 id="disclaimer-hi" style="font-size:1rem;color:#f5f5f5;margin:8px 0 12px 0;font-weight:600;">अस्वीकरण</h3>
          <ul class="disclaimer-list" lang="hi">
            <li>यह ऐप केवल जीएसटी/वैट/सीएसटी/उत्पाद शुल्क की चोरी से संबंधित जानकारी प्रदान करने के लिए है। कोई अन्य
              जानकारी नहीं दी जानी चाहिए।</li>
            <li>कृपया ध्यान दें कि जानकारी प्रदान करने से सूचना देने वाले को कोई पुरस्कार नहीं मिलेगा।</li>
            <li>सूचना देने वाले का नाम गुप्त रखा जाएगा।</li>
          </ul>
        </div>
        <div class=" mt-5">
          <a href="{{ route('login') }}" class="btn-custom text-decoration-none">
            Click to Register / Login
          </a>
        </div>

      </div>

      <div class="hero-image">
        <!-- <img src="./assets/images/new-tex-image.png" alt="Excise Department Illustration"> -->
         <!-- entery_page_image -->
        <img src="{{ asset('assets/images/entery_page_image/new-tex-image.png') }}" alt="Excise Department Illustration">
      </div>
    </div>
  </section>
   @if(session('status'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Logged Out',
            text: '{{ session('status') }}',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

</body>

</html>