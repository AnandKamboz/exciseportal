<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Excise Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
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
.hidden { display: none; }
.alert { border-radius: 15px; text-align: center; }
</style>
</head>
<body>

<div class="login-card">
    <h3>Excise Login</h3>
    <form id="loginForm">
        @csrf
        <div id="mobileSection" class="mb-3">
            <label class="form-label">Mobile Number</label>
            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter 10 digit mobile">
            <button type="button" id="sendOtpBtn" class="btn btn-primary w-100 btn-custom mt-3">Send OTP</button>
        </div>

        <div id="successMsg" class="alert alert-success mt-3 hidden"></div>

        <div id="otpSection" class="hidden mt-3">
            <label class="form-label">Enter OTP</label>
            <input type="text" name="otp" id="otp" class="form-control otp-input" maxlength="6" placeholder="Enter 6 digit OTP">
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

// Allow only numbers
mobileInput.addEventListener('input', () => {
    mobileInput.value = mobileInput.value.replace(/[^0-9]/g,'').slice(0,10);
});
otpInput.addEventListener('input', () => {
    otpInput.value = otpInput.value.replace(/[^0-9]/g,'').slice(0,6);
});

// Send OTP
sendOtpBtn.addEventListener('click', () => {
    if(mobileInput.value.length !== 10){
        alert('Enter a valid 10-digit mobile number!');
        return;
    }
    sendOtpBtn.disabled = true;
    sendOtpBtn.innerText = 'Sending...';
    axios.post('/send-otp', { mobile: mobileInput.value })
    .then(res => {
        if(res.data.success){
            successMsg.innerText = res.data.message || 'OTP sent successfully!';
            successMsg.classList.remove('hidden');
            otpSection.classList.remove('hidden');
            mobileSection.classList.add('hidden');
        } else {
            alert(res.data.message || 'Something went wrong!');
        }
    })
    .catch(err => {
        alert(err.response?.data?.message || 'Server error!');
    })
    .finally(() => {
        sendOtpBtn.disabled = false;
        sendOtpBtn.innerText = 'Send OTP';
    });
});

// Final login submit
loginBtn.addEventListener('click', () => {
    if(otpInput.value.length !== 6){
        alert('Enter 6-digit OTP');
        return;
    }

    axios.post('/verify-otp', {
        mobile: mobileInput.value,
        otp: otpInput.value,
        _token: document.querySelector('input[name="_token"]').value
    })
    .then(res => {
        if(res.data.success){
            window.location.href = res.data.redirect || '/complainant';
        } else {
            alert(res.data.message || 'Invalid OTP!');
        }
    })
    .catch(err => {
        alert(err.response?.data?.message || 'Server error!');
    });
});
</script>

</body>
</html>
