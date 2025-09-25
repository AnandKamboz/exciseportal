<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Complaint Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            padding: 3rem 0;
        }

        .card {
            width: 500px;
            padding: 2.5rem;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        .step {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .step.active {
            display: block;
        }

        .btn-step {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s;
        }

        .btn-step:hover {
            transform: scale(1.05);
        }

        h5 {
            color: #333;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            border-radius: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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
</head>

<body>

    <div id="loader" class="loader-overlay d-none">
        <div class="spinner"></div>
    </div>

    <div class="card">
        <form id="complaintForm" onsubmit="submitFinalStep(event)" enctype="multipart/form-data">
            <!-- Step 1 -->
            <div class="step active" id="step1">
                <h5 class="mb-3">Complainant Details</h5>
                <input type="text" name="complainant_name" class="form-control mb-2" placeholder="Enter your Name">
                <input type="text" name="phone" class="form-control mb-2" value="{{ $userMobile }}"
                    placeholder="Enter your Phone" readonly>
                <input type="email" name="email" class="form-control mb-2" placeholder="Enter your Email">
                <input type="text" name="address" class="form-control mb-2" placeholder="Enter your Address">
                <input type="text" name="aadhaar" class="form-control mb-2" placeholder="Enter your Aadhaar No.">
                <select name="complaint_type" id="complaintCategory" class="form-select mb-3">
                    <option value="" selected disabled>Select Complaint Type</option>
                    <option value="vat">VAT</option>
                    <option value="gst">GST</option>
                    <option value="excise">Excise</option>
                </select>


                <input type="file" name="document_upload" class="form-control mb-2" accept=".jpg,.jpeg,.png,.pdf"
                    onchange="checkFileSize(this)">
                <div id="previewContainer" style="margin-top:10px;"></div>

                @if (!empty($userData->upload_document) && !empty($userData->upload_document))
                    <img id="existing-image" src="{{ asset('storage/' . $userData->upload_document) }}"
                        alt="Existing Document" style="max-width:200px; display:block; margin-bottom:10px;">
                @endif

                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-primary btn-step" onclick="nextStep()">Next</button>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step" id="step2">
                <h5 class="mb-3">GST Fraud/Evasion Check</h5>
                <p>Is complaint related to Fraud/Evasion?</p>
                <div class="d-flex flex-column">
                    <select id="fraudCheck" class="form-select mb-2" required>
                        <option value="" selected disabled>Select an option</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary btn-step" onclick="prevStep()">Back</button>
                    <button type="button" class="btn btn-primary btn-step" onclick="nextStep()">Next</button>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step" id="step3">
                <h5 class="mb-3">Complaint Details</h5>
                <input type="text" name="firm_name" class="form-control mb-2" placeholder="Enter Firm Name"
                    oninput="sanitizeFirmName(this)">
                <input type="text" name="address_detail" class="form-control mb-2" placeholder="Enter Address"
                    oninput="sanitizeAddress(this)">


                <textarea name="remarks" class="form-control mb-2" placeholder="Remarks" oninput="sanitizeRemarks(this)"></textarea>
                <input type="text" name="gstin" class="form-control mb-2" placeholder="Enter gstin" id="gstin">
                <input type="file" name="document" class="form-control mb-2">


                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary btn-step" onclick="prevStep()">Back</button>
                    <button type="submit" class="btn btn-success btn-step">Submit Complaint</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;

        function showStep(step) {
            document.querySelectorAll('.step').forEach((el, i) => {
                el.classList.remove('active');
                if (i + 1 === step) el.classList.add('active');
            });
        }

        function nextStep() {
            if (currentStep === 1) return submitStep1();
            if (currentStep === 2) return submitStep2();
            if (currentStep === 3) return submitFinalStep();
        }

        function prevStep() {
            const category = document.getElementById('complaintCategory')?.value;
            if (currentStep === 3 && category !== 'gst') currentStep -= 2;
            else currentStep--;
            if (currentStep < 1) currentStep = 1;
            showStep(currentStep);
        }

        function submitStep1() {
            $('#loader').removeClass('d-none');
            const complainant_name = document.querySelector('input[name="complainant_name"]').value.trim();
            const mobile = document.querySelector('input[name="phone"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const address = document.querySelector('input[name="address"]').value.trim();
            const aadhaar = document.querySelector('input[name="aadhaar"]').value.trim();
            const category = document.getElementById('complaintCategory').value;
            const upload_document = document.querySelector('input[name="document_upload"]').files[0];



            if (!complainant_name) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter your name!',
                    confirmButtonText: 'OK'
                });
            }

            if (!mobile || mobile.length !== 10) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter valid 10-digit mobile!',
                    confirmButtonText: 'OK'
                });
            }

            if (!category) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Select complaint type!',
                    confirmButtonText: 'OK'
                });
            }

            if (aadhaar && aadhaar.length !== 12) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter valid 12-digit Aadhaar!',
                    confirmButtonText: 'OK'
                });
            }

            if (email && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter valid email!',
                    confirmButtonText: 'OK'
                });
            }

            const userData = @json($userData);


            if (!upload_document && !userData['upload_document']) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Please upload a document!',
                    confirmButtonText: 'OK'
                });
            }

            if (upload_document) {
                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

                if (!allowedTypes.includes(upload_document.type)) {
                    $('#loader').addClass('d-none');
                    return Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Only JPG, PNG, or PDF files are allowed!',
                        confirmButtonText: 'OK'
                    });
                }

                const maxSize = 50 * 1024;
                if (upload_document.size > maxSize) {
                    $('#loader').addClass('d-none');
                    return Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'File size must be less than 50 KB!',
                        confirmButtonText: 'OK'
                    });
                }
            }

            let formData = new FormData();
            formData.append('complainant_name', complainant_name);
            formData.append('mobile', mobile);
            formData.append('email', email);
            formData.append('address', address);
            formData.append('aadhaar', aadhaar);
            formData.append('complaint_type', category);
            formData.append('upload_document', upload_document);

            axios.post("{{ route('complaints.step-first') }}", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    $('#loader').addClass('d-none');
                    if (category !== 'gst') currentStep += 2;
                    else currentStep++;
                    showStep(currentStep);
                })
                .catch(err => {
                    $('#loader').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.response?.data?.message || 'Server error!',
                        confirmButtonText: 'OK'
                    });
                });
        }

        function submitStep2() {
            $('#loader').removeClass('d-none');
            const fraudCheck = document.getElementById('fraudCheck').value;

            if (!fraudCheck) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Select Fraud/Evasion option!',
                    confirmButtonText: 'OK'
                });
                $('#loader').addClass('d-none');
                return false;
            }

            axios.post("{{ route('complaints.step-second') }}", {
                    fraud_check: fraudCheck
                })
                .then(res => {
                    currentStep++;
                    showStep(currentStep);
                    $('#loader').addClass('d-none');
                })
                .catch(err => {
                    $('#loader').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.response?.data?.message || 'Server error!',
                        confirmButtonText: 'OK'
                    });
                });

        }


        function submitFinalStep(event) {
            event.preventDefault();
            $('#loader').removeClass('d-none');

            const firm_name = document.querySelector('input[name="firm_name"]').value.trim();
            const firm_address = document.querySelector('input[name="address_detail"]').value.trim();
            const proof_document = document.querySelector('input[name="document"]').files[0];
            const remarks = document.querySelector('textarea[name="remarks"]').value.trim();
            const gstin = document.getElementById('gstin').value;

            let formData = new FormData();
            formData.append('firm_name', firm_name);
            formData.append('firm_address', firm_address);
            formData.append('proof_document', proof_document);
            formData.append('remarks', remarks);
            formData.append('gstin', gstin);

            axios.post("{{ route('complaints.store') }}", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    $('#loader').addClass('d-none');

                    Swal.fire({
                        icon: 'success',
                        title: 'Complaint Submitted',
                        text: 'Your complaint has been submitted successfully. Complaint ID: ' + res.data
                            .complaint_id,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });
                })
                .catch(err => {
                    $('#loader').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.response?.data?.message || 'Server error!',
                        confirmButtonText: 'OK'
                    });
                });
        }

        function validateStep(step) {
            const stepEl = document.querySelector(`#step${step}`);
            const inputs = stepEl.querySelectorAll('input, select, textarea');
            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        }



        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.querySelector('input[name="complainant_name"]');
            const addressInput = document.querySelector('input[name="address"]');
            const phoneInput = document.querySelector('input[name="phone"]');
            const aadhaarInput = document.querySelector('input[name="aadhaar"]');
            const emailInput = document.querySelector('input[name="email"]');

            nameInput.addEventListener('input', () => {
                nameInput.value = nameInput.value.replace(/[^a-zA-Z\s]/g, '');
            });
            addressInput.addEventListener('input', () => {
                addressInput.value = addressInput.value.replace(/[^a-zA-Z0-9\s]/g, '');
            });
            phoneInput.addEventListener('input', () => {
                phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '').slice(0, 10);
            });
            aadhaarInput.addEventListener('input', () => {
                aadhaarInput.value = aadhaarInput.value.replace(/[^0-9]/g, '').slice(0, 12);
            });
            emailInput.addEventListener('blur', () => {
                const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
                if (emailInput.value && !emailPattern.test(emailInput.value)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Email',
                        text: 'Please enter a valid email address!',
                        confirmButtonText: 'OK'
                    });
                    emailInput.value = '';
                }
            });
        });

        function checkFileSize(input) {
            $('#existing-image').addClass('d-none');
            if (!input.files || !input.files[0]) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Missing',
                    text: 'Please select a file.'
                });
                input.value = '';
                return false;
            }

            const file = input.files[0];
            const maxSize = 200 * 1024;

            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must be equal to or less than 200 KB.'
                });
                input.value = '';
                return false;
            }

            const fileBlob = new Blob([file], {
                type: file.type
            });
            console.log('Blob created:', fileBlob);

            const blobUrl = URL.createObjectURL(fileBlob);
            console.log('Blob URL:', blobUrl);



            if (file.type.startsWith('image/')) {
                previewContainer.innerHTML = '';

                const img = document.createElement('img');
                img.src = blobUrl;
                img.style.maxWidth = '200px';
                img.style.marginTop = '10px';
                previewContainer.appendChild(img);
            }


            if (file.type === 'application/pdf') {
                previewContainer.innerHTML = '';
                const link = document.createElement('a');
                link.href = blobUrl;
                link.target = '_blank';
                link.textContent = 'Open PDF';
                link.style.display = 'block';
                link.style.marginTop = '10px';
                previewContainer.appendChild(link);
            }

            return true;
        }

        function sanitizeFirmName(input) {
            input.value = input.value.replace(/[^\w\s\-.,&]/g, '');
        }

        function sanitizeAddress(input) {
            input.value = input.value.replace(/[^\w\s\-.,\/&]/g, '');
        }

        function sanitizeRemarks(input) {
            input.value = input.value.replace(/[^\w\s\-.,\/&!?]/g, '');
        }

        $(document).ready(function() {
            axios.get("{{ route('user.data') }}")
                .then(function(response) {
                    $('input[name="complainant_name"]').val(response.data.complaints.complainant_name);
                    $('input[name="address"]').val(response.data.complaints.address);
                    $('input[name="aadhaar"]').val(response.data.complaints.aadhaar);
                    $('input[name="email"]').val(response.data.complaints.email);
                    $('#complaintCategory').val(response.data.complaints.complaint_type);

                    const existingImg = document.getElementById('existing-image');
                    existingImg.src = "{{ asset('storage') }}/" + response.data.complaints.upload_document;
                    existingImg.style.display = 'block';
                })
                .catch(function(error) {
                    console.error('Error:', error.response);
                });
        })
    </script>


</body>

</html>
