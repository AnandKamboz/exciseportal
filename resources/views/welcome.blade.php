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
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
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
        /* Overlay */
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

        /* Spinner */
        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #4caf50;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Spin animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Hide when d-none is present */
        .loader-overlay.d-none {
            display: none !important;
        }
    </style>

    {{-- ak --}}

    <div class="login-card">
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
    </div>

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
