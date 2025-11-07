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
            letter-spacing: 12px;
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
            position: relative;
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
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
            background-color: hsl(218, 41%, 15%);
            background-image: radial-gradient(650px circle at 0% 0%,
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
                    transparent 100%);
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
            box-shadow: 0 0 40px rgba(255, 255, 255, 0.3);
        }

        /* =================== Form Styling =================== */
        .card-body {
            color: #fff;
            font-family: "Poppins", sans-serif;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 12px;
            color: #fff;
            padding: 10px 14px;
            transition: 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #404ee8;
            box-shadow: 0 0 12px rgba(64, 78, 232, 0.5);
            color: #fff;
        }

        .form-label {
            color: #eee;
            font-weight: 500;
            margin-top: 5px;
        }

        .btn-primary {
            background-color: #b028ff;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background-color: #852ab9;
        }

        .form-check-label {
            color: #f1f1f1;
        }

        .btn-floating {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
        }

        .btn-floating:hover {
            background: linear-gradient(135deg, #404ee8, #ef3b3b);
        }

        .text-center p {
            color: #fff;
            font-weight: 500;
            margin-bottom: 10px;
        }

        ::placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
            opacity: 1 !important;
        }
    </style>
</head>

<body>
    <div id="loader" class="loader-overlay d-none">
        <div class="spinner"></div>
    </div>

    <section class="background-radial-gradient overflow-hidden login-page">
        <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5">
                <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                    <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
                        The dummy text <br />
                        <span style="color: hsl(218, 81%, 75%)">for your xyz</span>
                    </h1>
                    <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%)">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        Temporibus, expedita iusto veniam atque, magni tempora mollitia
                        dolorum consequatur nulla, neque debitis eos reprehenderit quasi
                        ab ipsum nisi dolorem modi. Quos?
                    </p>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>
                    <div class="card bg-glass">
                        <div class="card-body px-4 py-5 px-md-5">
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/images/banner/haryana-logo.png') }}" alt="Logo" width="80"
                                    height="80" class="rounded-circle">
                            </div>
                            <h4 class="text-center mb-4 fw-bold">Excise and Taxation Department</h4>

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
                                        {{session('captcha') }}
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


            axios.post("{{ route('verify-otp') }}", {
                mobile: mobileInput.value,
                otp: otpInput.value,
                captcha: captchaInput.value,
                _token: document.querySelector('input[name="_token"]').value
            })
                .then(res => {
                    if (res.data.success) {
                        $('#loader').addClass('d-none');
                        window.location.href = res.data.redirect_url;
                    } else {
                        $('#loader').addClass('d-none');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.data.message || 'Invalid OTP!',
                            confirmButtonText: 'OK'
                        });
                        $('#otp').val("");
                        loginBtn.disabled = false;
                        loginBtn.innerText = 'Send Otp';
                    }
                })
                .catch(err => {
                    $('#loader').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: err.response?.data?.message || 'Server error!',
                        confirmButtonText: 'OK'
                    });
                    loginBtn.disabled = false;
                    loginBtn.innerText = 'Login';
                    return;
                });
        });
    </script>

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
