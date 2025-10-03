<!DOCTYPE html>
<html lang="en">
{{-- Head --}}

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
        {{-- body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        } --}} .login-card {
            background: #fff;
            border-radius: 25px;
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        h3 {
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-bottom: 2rem;
        }

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
    </style>
</head>

<body>
    <!-- Loader HTML -->
    <div id="loader" class="loader-overlay d-none">
        <div class="spinner"></div>
    </div>

    <style>
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
    </style>

    {{-- ak --}}







    {{-- --------------------new-design----------------------- --}}


    <style>
        .login-page {
            position: relative;
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(rgb(0 0 0 / 50%), rgb(0 0 0 / 50%)),
                url(/assets/images/banner/IT-Act.png) center / cover no-repeat;



        }

        .login-page .bubble {
            position: absolute;
            border-radius: 50%;
            /* background: radial-gradient(circle, #23e9ff, #5488a1, #435362); */
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
            {{-- width: 70%; --}} margin-bottom: 20px;
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
    </style>

    <div class="login-page">
        <div class="bubble bubble1"></div>
        <div class="bubble bubble2"></div>
        <div class="bubble bubble3"></div>
        <div class="bubble bubble4"></div>

        <div class="login-glass">

            <div class="logo-container">
                <img src="{{ asset('assets/images/banner/haryana-logo.png') }}" alt="Logo" class="login-logo">
            </div>

            <h2>LOGIN</h2>
            <form id="loginForm">
                @csrf
                <!-- Mobile Section -->
                <div id="mobileSection" class="mb-3">
                    <input type="text" name="mobile" id="mobile" class="form-control"
                        placeholder="Enter 10 digit mobile" />
                    <button type="button" id="sendOtpBtn" class="btn btn-submit w-100 mt-3">
                        Send OTP
                    </button>
                </div>

                <!-- Success Message -->
                <div id="successMsg" class="alert alert-success mt-3 hidden"></div>

                <!-- OTP Section -->
                <div id="otpSection" class="hidden mt-3">
                    <input type="text" name="otp" id="otp" class="form-control otp-input" maxlength="6"
                        placeholder="Enter 6 digit OTP" />
                    <button type="button" id="loginBtn" class="btn btn-submit w-100 mt-3">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>



    {{-- --------------------new-design----------------------- --}}




    {{-- <div class="login-card">
        <h3>Login</h3>
        <form id="loginForm">
            @csrf
            <div id="mobileSection" class="mb-3">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile" id="mobile" class="form-control"
                    placeholder="Enter 10 digit mobile">
                <button type="button" id="sendOtpBtn" class="btn btn-primary w-100 btn-custom mt-3">Send OTP</button>
            </div>

            <div id="successMsg" class="alert alert-success mt-3 hidden"></div>

            <div id="otpSection" class="hidden mt-3">
                <label class="form-label">Enter OTP</label>
                <input type="text" name="otp" id="otp" class="form-control otp-input" maxlength="6"
                    placeholder="Enter 6 digit OTP">
                <button type="button" id="loginBtn" class="btn btn-success w-100 mt-3 btn-custom">Login</button>
            </div>
        </form>
    </div> --}}

    <script>
        const mobileInput = document.getElementById('mobile');
        const otpInput = document.getElementById('otp');
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
                return;
            }

            axios.post("{{ route('verify-otp') }}", {
                    mobile: mobileInput.value,
                    otp: otpInput.value,
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

                });
        });
    </script>
</body>

</html>
{{--  --}}
