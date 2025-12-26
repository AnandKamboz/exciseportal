<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Account Deletion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card p-4">
                    <h4 class="text-center text-danger mb-3">
                        Account Deletion
                    </h4>

                    <p class="text-muted text-center">
                        Enter your registered mobile number to continue
                    </p>

                    <!-- STEP 1 : MOBILE -->
                    <div id="mobileSection">
                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" id="mobile" class="form-control" placeholder="Enter mobile number"
                                maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        </div>

                        <button class="btn btn-primary w-100" onclick="sendOtp()">
                            Submit
                        </button>
                    </div>

                    <!-- STEP 2 : OTP -->
                    <div id="otpSection" class="hidden mt-4">
                        <div class="mb-3">
                            <label class="form-label">Enter 6-digit OTP</label>
                            <input type="password" id="otp" class="form-control text-center" placeholder="******"
                                maxlength="6" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        </div>

                        <button class="btn btn-success w-100" onclick="verifyOtp()">
                            Verify OTP & Delete Account 
                        </button>
                    </div>

                    <!-- STEP 3 : DELETE -->
                    {{-- <div id="deleteSection" class="hidden mt-4">
                        <button class="btn btn-danger w-100" onclick="deleteAccount()">
                            Delete Account
                        </button>
                    </div> --}}

                </div>

            </div>
        </div>
    </div>

    <script>
        // function sendOtp() {
        //     const mobile = document.getElementById('mobile').value;
             
        //     if (mobile.length !== 10) {
        //         Swal.fire('Error', 'Please enter a valid 10-digit mobile number', 'error');
        //         return;
        //     }

        //     Swal.fire({
        //         icon: 'success',
        //         title: 'OTP Sent',
        //         text: 'A 6-digit OTP has been sent to your mobile number'
        //     });

        //     document.getElementById('otpSection').classList.remove('hidden');
        // }

        function sendOtp() {
                const mobile = document.getElementById('mobile').value;

                if (mobile.length !== 10) {
                    Swal.fire('Error', 'Please enter a valid 10-digit mobile number', 'error');
                    return;
                }

                fetch("{{ url('/check-mobile-and-send-otp') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ mobile: mobile })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Sent',
                            text: data.message
                        });

                        document.getElementById('otpSection').classList.remove('hidden');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Server error. Please try again.', 'error');
                });
            }

        function verifyOtp() {
            const otp = document.getElementById('otp').value;
            const mobile = document.getElementById('mobile').value;

            if (otp.length !== 6) {
                Swal.fire('Error', 'Please enter a valid 6-digit OTP', 'error');
                return;
            }

            fetch("{{ url('/verify-delete-otp') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        mobile: mobile,
                        otp: otp
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Verified',
                            text: data.message
                        });

                        document.getElementById('deleteSection').classList.remove('hidden');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Server error. Please try again.', 'error');
                });
        }


        function deleteAccount() {
            Swal.fire({
                icon: 'success',
                title: 'Account Deleted',
                text: 'Your account has been deleted successfully'
            });
        }
    </script>

</body>

</html>
