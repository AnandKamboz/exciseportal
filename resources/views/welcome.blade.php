<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excise Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        /* =================== General Form Styles =================== */
        .form-control {
            border-radius: 15px;
            padding: 0.75rem;
            border: 1px solid #d1d9e6;
        }

        .btn-custom {
            border-radius: 15px;
            font-weight: 600;
        }

        .otp-input {
            font-size: 20px;
            text-align: center;
        }

        .hidden {
            display: none;
        }

        .alert {
            border-radius: 15px;
            text-align: center;
        }

        /* =================== Loader Styles =================== */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #4caf50;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loader-overlay.d-none {
            display: none !important;
        }

        /* =================== Login Page =================== */
        .login-page {
            /* position: relative;
            height: 100vh;
            width: 100%;
            
            align-items: center;
            justify-content: center;
            overflow: hidden; */
            display: flex;
        }

        .login-page .bubble {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at center, #ffecd2, #fcb69f, #ff9a9e, #ff6a00);
            filter: blur(0px);
        }

        .bubble1 {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 25%;
        }

        .bubble2 {
            width: 100px;
            height: 100px;
            top: 15%;
            right: 25%;
        }

        .bubble3 {
            width: 120px;
            height: 120px;
            bottom: 15%;
            left: 30%;
        }

        .bubble4 {
            width: 80px;
            height: 80px;
            bottom: 20%;
            right: 30%;
        }

        /* =================== Glass Login Box =================== */
        .login-glass {
            position: relative;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 4px solid #fea49e;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }

        .login-glass h2 {
            font-weight: 700;
            margin-bottom: 25px;
        }

        .login-glass input {
            background: transparent;
            border: none;
            border-bottom: 2px solid #fff;
            color: #fff;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .login-glass input::placeholder {
            color: #ddd;
        }

        .login-glass .btn {
            width: 30%;
            background: #fdaea1;
            color: #fff;
            font-weight: bold;
            border: none;
        }

        .login-glass .btn:hover {
            background: #d19187ff;
        }

        .captcha-box {
            background: #1e1e1e;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
        }

        .captcha-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-control:focus {
            color: #fff;
            background: transparent;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .login-logo {
            max-width: 100px;
            height: 70px;
            width: 70px;
            background: #fff;
            padding: 5px;
            object-fit: contain;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* =================== Background Gradient =================== */
        .background-radial-gradient {
            /* background-color: hsl(218, 41%, 15%); */
            /* background-image: radial-gradient(650px circle at 0% 0%,
                    hsl(218, 41%, 35%) 15%,
                    hsl(218, 41%, 30%) 35%,
                    hsl(218, 41%, 20%) 75%,
                    hsl(218, 41%, 19%) 80%,
                    transparent 100%),
                radial-gradient(1250px circle at 100% 100%,
                    hsl(218, 41%, 45%) 15%,
                    hsl(218, 41%, 30%) 35%,
                    hsl(218, 41%, 20%) 75%,
                    hsl(218, 41%, 19%) 80%,
                    transparent 100%); */
        }

        #radius-shape-1 {
            height: 220px;
            width: 220px;
            top: -60px;
            left: -130px;
            background: radial-gradient(#44006b, #ad1fff);
            overflow: hidden;
        }

        #radius-shape-2 {
            border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
            bottom: -60px;
            right: -110px;
            width: 300px;
            height: 300px;
            background: radial-gradient(#44006b, #ad1fff);
            overflow: hidden;
        }

        /* =================== Glass Effect Container =================== */
        .bg-glass {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(60px) saturate(160%);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.25);
            transition: all 0.4s ease;
        }

        .bg-glass:hover {
            transform: translateY(-6px);
            /* box-shadow: 0 0 40px rgba(255, 255, 255, 0.3); */
        }

        /* =================== Form Styling =================== */
        .card-body {
            color: #393939;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            height: 380px;
        }

        .form-control {
            background: rgb(13 110 253 / 4%);
            border: 1px solid rgba(13, 110, 253, 0.25);
            border-radius: 12px;
            color: #393939;
            padding: 10px 14px;
            transition: 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #404ee8;
            box-shadow: 0 0 12px rgba(64, 78, 232, 0.5);
            color: #393939;
        }

        .form-label {
            color: #393939;
            font-weight: 500;
            margin-top: 5px;
        }

        .btn-primary {
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

        .form-check-label {
            color: #272727;
        }

        .btn-floating {
            color: #393939 !important;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
        }

        .btn-floating:hover {
            background: linear-gradient(135deg, #404ee8, #ef3b3b);
        }

        .text-center p {
            color: #393939;
            font-weight: 500;
            margin-bottom: 10px;
        }

        ::placeholder {
            color: #393939 !important;
            opacity: 1 !important;
        }



        .top-bar {
            width: 100%;
            background: linear-gradient(135deg, #7386ff, #262626);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 20px;
            flex-wrap: wrap;
        }

        .top-bar .contact-item {
            display: flex;
            align-items: center;
            font-size: 15px;
        }

        .top-bar .contact-item i {
            margin-right: 8px;
        }

        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            border-bottom: 2px solid #7386ff;
            background: linear-gradient(to right, #ffffff, #f1f1f1);
            flex-wrap: wrap;
        }

        .header img {
            height: 90px;
        }

        .header-content {
            text-align: center;
            flex: 1;
        }

        .header-content h1 {
            font-size: 26px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 5px 0;
            letter-spacing: 1px;
        }

        .header-content p {
            font-size: 17px;
            color: #555;
            margin: 0;
            font-weight: 500;
        }

        .kar-hit {
            font-size: 1.2rem;
            line-height: 80px;
            margin-bottom: 0;
            font-weight: 700;
            background: linear-gradient(90deg, #3beaef, #404ee8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <div id="loader" class="loader-overlay d-none">
        <div class="spinner"></div>
    </div>


    <!-- Top Bar -->
    <div class="top-bar">
        <div class="contact-item">
            <i>📧</i> excisehry@gmail.com
        </div>
        <div class="contact-item">
            <i>📞</i> 0123-4567890
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="d-flex align-items-center me-3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/36/Emblem_of_Haryana.svg" alt="Logo"
                style="height: 60px; width: auto;">
            <span class="ms-2 fw-bold kar-hit">कर हितैषी</span>
        </div>
        <div class="header-content">
            <h1>Excise and Taxation Department</h1>
            <p>Government of Haryana</p>
        </div>
    </header>



    <section class="background-radial-gradient  login-page">
        <div class="container px-4  px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5 ">


                <div class="col-lg-7 mb-5 mb-lg-0" style="z-index: 10;">

                    {{--
                    <div class="p-4 rounded-4 shadow-lg"
                        style="background: linear-gradient(135deg, #153462, #1A4D8C); color: #f1f6ff; border-left: 6px solid #00c4ff;  font-size: 14px;">
                        <h5 class="fw-semibold text-warning mb-2">Disclaimer</h5>
                        <ul style="list-style-type: square; padding-left: 1.5rem; line-height: 1.7;">
                            <li>This app pertains to providing information related to evasion of <strong>GST / VAT / CST
                                    / Excise</strong> only. No other information should be given.</li>
                            <li>The name of the informer will be kept secret.</li>
                        </ul>

                        <hr style="border-top: 1px dashed rgba(255,255,255,0.3); margin: 1.5rem 0;">

                        <h5 class="fw-semibold text-warning mb-2">अस्वीकरण</h5>
                        <ul style="list-style-type: square; padding-left: 1.5rem; line-height: 1.7;">
                            <li>यह ऐप केवल <strong>जीएसटी / वैट / सीएसटी / उत्पाद शुल्क</strong> की चोरी से संबंधित
                                जानकारी प्रदान करने के लिए है। कोई अन्य जानकारी नहीं दी जानी चाहिए।</li>
                            <li>सूचना देने वाले का नाम गुप्त रखा जाएगा।</li>
                        </ul>
                    </div>
                    --}}


                    <!-- Image version (replace the src path as needed) -->
                    <div class="p-0 rounded-4 shadow-lg overflow-hidden" style="background: #fff;">
                        <img src="{{ asset('assets/images/entery_page_image/login-image.jpg') }}"
                            alt="Disclaimer Graphic" class="img-fluid w-100 d-block"
                            style="object-fit: cover; max-height: 480px;">
                    </div>

                </div>



                <div class="col-lg-5 mb-5 mb-lg-0 position-relative">
                    {{-- <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div> --}}
                    <div class="card bg-glass">
                        <div class="card-body px-4 py-5 px-md-5">
                            {{-- <div class="text-center mb-4">
                                <img src="{{ asset('assets/images/banner/haryana-logo.png') }}" alt="Logo" width="80"
                                    height="80" class="rounded-circle">
                            </div> --}}
                            <h4 class="text-center mb-4 fw-bold">Login/Register</h4>

                            <form id="loginForm">
                                @csrf
                                <div id="mobileSection" class="mb-3">
                                    <input type="text" name="mobile" id="mobile" class="form-control"
                                        placeholder="Enter 10 digit mobile" />
                                    <button type="button" id="sendOtpBtn"
                                        class="btn btn-primary btn-block w-100 mt-3">Send OTP</button>
                                </div>

                                <div id="successMsg" class="alert alert-success mt-3 hidden"></div>

                                <div class="form-outline mb-4 d-flex align-items-center justify-content-between d-none"
                                    id="captcha_code">
                                    <div class="captcha bg-white text-dark px-3 py-2 rounded fw-bold">
                                        {{-- {{ session('captcha') }} --}}
                                        <img src="{{ captcha_src() }}" id="captcha" alt="captcha" width="150"
                                            height="50">

                                        <button type="button"
                                            class="btn btn-primary btn-sm rounded-3 d-flex align-items-center mt-1"
                                            style="padding: 2px 6px; font-size: 0.75rem;" id="reload-captcha-btn"
                                            onclick="reloadCaptcha()">
                                            <span id="btn-text"><i class="bi bi-arrow-clockwise me-1"
                                                    style="font-size: 0.75rem;"></i> Reload</span>
                                            <span id="btn-spinner" class="spinner-border spinner-border-sm ms-1 d-none"
                                                role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                    <input type="text" id="captchaInput" class="form-control w-50"
                                        placeholder="Enter Captcha" />
                                </div>

                                <div id="otpSection" class="hidden mt-3">
                                    <input type="text" name="otp" id="otp" class="form-control otp-input" maxlength="6"
                                        placeholder="Enter 6 digit OTP" />
                                    <button type="button" id="loginBtn"
                                        class="btn btn-primary btn-block w-100 mt-3">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <script>
        function reloadCaptcha() {
                document.getElementById('captcha').src = "{{ captcha_src() }}" + '?' + Date.now();
        }
    </script> --}}
    <script>
        function reloadCaptcha() {
           $('#loader').removeClass('d-none');
           $('#captchaInput').val('');
            var img = document.getElementById('captcha');
            var btnSpinner = document.getElementById('btn-spinner');
            var btnText = document.getElementById('btn-text');

            btnSpinner.classList.remove('d-none');
            btnText.classList.add('d-none');

            img.src = '{{ captcha_src() }}?' + Date.now();

            img.onload = function() {
                btnSpinner.classList.add('d-none');
                btnText.classList.remove('d-none');
                $('#loader').addClass('d-none');
            };
            // $('#loader').addClass('d-none');
        }
    </script>
    <script>
        const mobileInput = document.getElementById('mobile');
        const otpInput = document.getElementById('otp');
        const captchaInput = document.getElementById('captchaInput');
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const loginBtn = document.getElementById('loginBtn');
        const successMsg = document.getElementById('successMsg');
        const otpSection = document.getElementById('otpSection');
        const mobileSection = document.getElementById('mobileSection');

        mobileInput.addEventListener('input', () => {
            mobileInput.value = mobileInput.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
        otpInput.addEventListener('input', () => {
            otpInput.value = otpInput.value.replace(/[^0-9]/g, '').slice(0, 6);
        });

        sendOtpBtn.addEventListener('click', () => {
            $('#loader').removeClass('d-none');
            if (mobileInput.value.length !== 10) {
                $('#loader').addClass('d-none');
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Input',
                    text: 'Enter a valid 10-digit mobile number!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            sendOtpBtn.disabled = true;
            sendOtpBtn.innerText = 'Sending...';
            axios.post("{{ route('send-otp') }}", {
                    mobile: mobileInput.value
                })
                .then(res => {
                    if (res.data.success) {
                        $('#loader').addClass('d-none');
                        successMsg.innerText = res.data.message || 'OTP sent successfully!';
                        successMsg.classList.remove('hidden');
                        otpSection.classList.remove('hidden');
                        mobileSection.classList.add('hidden');
                        $('#captcha_code').removeClass('d-none');
                    } else {
                        $('#loader').addClass('d-none');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.data.message || 'Something went wrong!',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(err => {
                    $('#loader').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: err.response?.data?.message || 'Server error!',
                        confirmButtonText: 'OK'
                    });
                })
                .finally(() => {
                    $('#loader').addClass('d-none');
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.innerText = 'Send OTP';
                });
        });

        loginBtn.addEventListener('click', () => {
            $('#loader').removeClass('d-none');
            loginBtn.disabled = true;
            loginBtn.innerText = 'Please Wait...';

            if (otpInput.value.length !== 6) {
                $('#loader').addClass('d-none');
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Enter 6-digit OTP',
                    confirmButtonText: 'OK'
                });
                loginBtn.disabled = false;
                loginBtn.innerText = 'Login';
                return;
            }

            // Captcha validation
            if (captchaInput.value.trim() === '') {
                $('#loader').addClass('d-none');
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please Enter 6 Digit Captcha',
                    confirmButtonText: 'OK'
                });
                loginBtn.disabled = false;
                loginBtn.innerText = 'Login';
                return;
            }


           

            // axios.post("{{ route('verify-otp') }}", {
            //         mobile: mobileInput.value,
            //         otp: otpInput.value,
            //         captcha: captchaInput.value,
            //         _token: document.querySelector('input[name="_token"]').value
            //     })
            //     .then(res => {
            //         $('#loader').addClass('d-none');

            //         if (res.data.success) {
            //             window.location.href = res.data.redirect_url;
            //         } else {
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Oops...',
            //                 text: res.data.message || 'Invalid OTP!',
            //                 confirmButtonText: 'OK'
            //             }).then(() => {
            //                 if (res.data.refresh) {
            //                     location.reload();
            //                 }
            //             });

            //             $('#otp').val("");
            //             loginBtn.disabled = false;
            //             loginBtn.innerText = 'Send Otp';
            //         }
            //     })
            //     .catch(err => {
            //         $('#loader').addClass('d-none');
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Oops...',
            //             text: err.response?.data?.message || 'Server error!',
            //             confirmButtonText: 'OK'
            //         });
            //         loginBtn.disabled = false;
            //         loginBtn.innerText = 'Login';
            //     });

           axios.post("{{ route('verify-otp') }}", {
                    mobile: mobileInput.value,
                    otp: otpInput.value,
                    captcha: captchaInput.value,
                    _token: document.querySelector('input[name="_token"]').value
                })
                .then(res => {
                    $('#loader').addClass('d-none');

                   if (res.data.success) {
                        window.location.href = res.data.redirect_url;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#otp').val('');
                            $('#captcha-input').val('');
                            reloadCaptcha();
                            loginBtn.disabled = false;
                            loginBtn.innerText = 'Send Otp';
                        });
                    }
                })
                .catch(err => {
                    $('#loader').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oopssssss...',
                        text: err.response?.data?.message || 'Server error!',
                        confirmButtonText: 'OK'
                    });
                    loginBtn.disabled = false;
                    loginBtn.innerText = 'Send Otp';
                });

        });
    </script>

    @if (session('status'))
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