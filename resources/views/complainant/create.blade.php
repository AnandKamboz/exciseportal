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
            {{-- background: linear-gradient(135deg, #ffecd2, #fcb69f, #ff9a9e, #ff6a00); --}} font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 3rem 0;
            min-height: 100vh;
        }

        .card {
            width: 600px;
            padding: 3rem 2rem;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
        }

        .step {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .step.active {
            display: block;
        }

        h5 {
            color: #ff6a00;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        input,
        select,
        textarea {
            border-radius: 12px;
            border: 1px solid #ccc;
            padding: 0.6rem 1rem;
            width: 100%;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #ff6a00;
            box-shadow: 0 0 8px rgba(255, 106, 0, 0.3);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .row .col-half {
            flex: 1;
        }

        .btn-step {
            border-radius: 20px;
            font-weight: 600;
            padding: 0.6rem 2rem;
            background: linear-gradient(90deg, #ffecd2, #fcb69f, #ff9a9e, #ff6a00);
            border: none;
            color: #fff;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(255, 106, 0, 0.3);
        }

        .btn-step:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 106, 0, 0.5);
        }

        .d-flex.justify-content-between {
            margin-top: 1.5rem;
        }

        #previewContainer img {
            max-width: 100px;
            margin-right: 10px;
            border-radius: 8px;
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
            <div class="step active" id="step1">
                <h5>Complainant Details</h5>



                <div class="mb-3">
                    <label for="complaint_type" class="form-label">
                        Complaint Type <span class="text-danger">*</span>
                    </label>
                    <select name="complaint_type" id="complaint_type" class="form-select" required>
                        <option value="" selected disabled>Select Complaint Type</option>
                        <option value="vat">VAT</option>
                        <option value="gst">GST</option>
                        <option value="excise">Excise</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-step" onclick="nextStep()">Next</button>
                </div>
            </div>





            {{-- Select 1 --}}

            <div class="step" id="step2">
                <h5>Complainant Details</h5>

                <div class="row">
                    <div class="col-half mb-3">
                        <label for="complainant_name" class="form-label">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="complainant_name" id="complainant_name" 
                            class="form-control" placeholder="Enter your Name" required>
                    </div>

                    <div class="col-half mb-3">
                        <label for="complainant_phone" class="form-label">
                            Phone <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="complainant_phone" id="complainant_phone" 
                            class="form-control" value="{{ $userMobile }}" 
                            placeholder="Enter your Phone" readonly>
                    </div>
                </div>


                <div class="row">
                    <div class="col-half mb-3">
                        <label for="complainant_email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="complainant_email" name="complainant_email" id="complainant_email" 
                            class="form-control" placeholder="Enter your Email" required>
                    </div>

                    <div class="col-half mb-3">
                        <label for="complainant_aadhaar" class="form-label">
                            Aadhaar No. <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="complainant_aadhaar" id="complainant_aadhaar" 
                            class="form-control" placeholder="Enter your Aadhaar No." required>
                    </div>
                </div>

                <!-- <input type="text" name="address" class="form-control mb-2" placeholder="Enter your Address"> -->

                <div class="row">
                    <div class="mb-2">
                        <label for="complainant_dist_id" class="form-label">
                            District <span class="text-danger">*</span>
                        </label>
                        <select name="complainant_dist_id" id="complainant_dist_id" class="form-select" required>
                            <option value="" disabled selected>Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="mb-2">
                        <label for="complainant_address" class="form-label mb-2">
                            Address <span class="text-danger">*</span>
                        </label>
                        <textarea name="complainant_address" id="complainant_address" 
                            class="form-control mb-3" placeholder="Enter your Address" rows="3" required></textarea>
                    </div>
                </div>

                <div class="mt-2">
                    <label for="document_upload" class="form-label">
                        Upload Document <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="document_upload" id="document_upload" 
                        class="form-control mb-2" 
                        accept=".jpg,.jpeg,.png,.pdf"
                        onchange="checkFileSize(this)">
                </div>

                <div id="previewContainer" style="margin-top:10px;"></div>


                @if (!empty($userData->upload_document))
                    <img id="existing-image" src="{{ asset('storage/' . $userData->upload_document) }}"
                        alt="Existing Document" style="max-width:100px; display:block; margin-bottom:10px;">
                @endif

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-step" onclick="prevStep()">Back</button>
                    <button type="button" class="btn btn-step" onclick="nextStep()">Next</button>
                </div>
            </div>














































            <!-- Step 2 -->
            <div class="step" id="step3">
                <h5>GST Fraud/Evasion Check</h5>
                <p>Is complaint related to Fraud/Evasion?</p>
                <select id="fraudCheck" class="form-select mb-2">
                    <option value="" selected disabled>Select an option</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-step" onclick="prevStep()">Back</button>
                    <button type="button" class="btn btn-step" onclick="nextStep()">Next</button>
                </div>
            </div>
            

            <!-- Step 3 -->
            <div class="step" id="step4">
                <h5>Complaint Details</h5>

                <div class="row">
                    <div class="col-half">

                            <div class="mb-3">
                                <label for="firm_name" class="form-label">
                                    Firm Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="firm_name" id="firm_name" 
                                    class="form-control" placeholder="Enter Firm Name" 
                                    oninput="sanitizeFirmName(this)">
                            </div>
                    </div>

                    <div class="col-half mb-3">
                        <label for="address_detail" class="form-label">
                            Address <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="address_detail" id="address_detail" 
                            class="form-control" placeholder="Enter Address"
                            oninput="sanitizeAddress(this)">
                    </div>
                </div>


                <div class="row">
                    <div class="col-half">

                        <!-- <input type="text" name="gstin" class="form-control" placeholder="Enter GSTIN"
                            id="gstin"> -->

                            <div class="mb-3">
                                <label for="gstin" class="form-label">
                                    GSTIN <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="gstin" id="gstin" 
                                    class="form-control" placeholder="Enter GSTIN">
                            </div>
                    </div>

                    <div class="col-half">
                         <div class="mb-3">
                            <label for="document" class="form-label">
                                Upload Document <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="document" id="document" 
                                class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>
                </div>


                <!-- h -->

                 <div class="row">
                    <div class="mb-2">
                        <label for="against_district_id" class="form-label">
                            District <span class="text-danger">*</span>
                        </label>
                        <select name="against_district_id" id="against_district_id" class="form-select">
                            <option value="" disabled selected>Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="mb-3">
                    <label for="remarks" class="form-label">
                        Remarks <span class="text-danger">*</span>
                    </label>
                    <textarea name="remarks" id="remarks" 
                            class="form-control mb-2" placeholder="Remarks" 
                            oninput="sanitizeRemarks(this)" rows="3" ></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-step" onclick="prevStep()">Back</button>
                    <button type="submit" class="btn btn-step">Submit Complaint</button>
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
            if (currentStep === 3) return submitStep3();
            if (currentStep === 4) return submitFinalStep();
        }

        function prevStep() {
            const category = document.getElementById('complaint_type')?.value;
            if (currentStep === 3 && category !== 'gst') currentStep -= 2;
            else currentStep--;
            if (currentStep < 1) currentStep = 1;
            showStep(currentStep);
        }
        

        // Submit Step First 
        function submitStep1() {
            $('#loader').removeClass('d-none');
            let complaint_type = $('#complaint_type').val();

            if (!complaint_type) {
                $('#loader').addClass('d-none');
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Select your Complaint Type!',
                    confirmButtonText: 'OK'
                });
                return; 
            }

            axios.post("{{ route('complaints.step-first') }}", {
                    complaint_type: complaint_type,
                })
                .then(function(response) {
                    $('#loader').addClass('d-none');
                    console.log(response.data);
                    currentStep++;
                    showStep(currentStep);
                })
                .catch(function(error) {
                    $('#loader').addClass('d-none');
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message || 'Something went wrong!',
                        confirmButtonText: 'OK'
                    });
            });
        }

        function submitStep2() {
            $('#loader').removeClass('d-none');
            const complainant_name = document.querySelector('input[name="complainant_name"]').value.trim();
            const complainant_dist_id = document.getElementById('complainant_dist_id')?.value;
            const complainant_phone = document.querySelector('input[name="complainant_phone"]').value.trim();
            const complainant_email = document.querySelector('input[name="complainant_email"]').value.trim();
            const complainant_address = $("#complainant_address").val();
            const complainant_aadhaar = document.querySelector('input[name="complainant_aadhaar"]').value.trim();
            const upload_document = document.querySelector('input[name="document_upload"]').files[0];
            const category = document.getElementById('complaint_type')?.value;


            if (!complainant_name) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter your name!',
                    confirmButtonText: 'OK'
                });
            }

            if (!complainant_phone || complainant_phone.length !== 10) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter valid 10-digit mobile!',
                    confirmButtonText: 'OK'
                });
            }

            if(!complainant_email) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter your email!',
                    confirmButtonText: 'OK'
                });
            }
            

           if (!complainant_aadhaar || !/^\d{12}$/.test(complainant_aadhaar)) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter a valid 12-digit Aadhaar!',
                    confirmButtonText: 'OK'
                });
            }

            if(!complainant_dist_id) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Select your District!',
                    confirmButtonText: 'OK'
                });
            }

            if (!complainant_address) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter your address!',
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

                // Check file type
                if (!allowedTypes.includes(upload_document.type)) {
                    $('#loader').addClass('d-none');
                    $('#upload_document').val(''); // reset file input
                    return Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Only JPG, PNG, or PDF files are allowed!',
                        confirmButtonText: 'OK'
                    });
                }

                // Check file size (50KB)
                const maxSize = 50 * 1024; 
                if (upload_document.size > maxSize) {
                    $('#loader').addClass('d-none');
                    $('#upload_document').val(''); // reset file input
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
            formData.append('complainant_phone', complainant_phone);
            formData.append('complainant_email', complainant_email);
            formData.append('complainant_address', complainant_address);
            formData.append('complainant_aadhaar', complainant_aadhaar);
            formData.append('upload_document', upload_document);
            formData.append('complainant_dist_id', complainant_dist_id);

            axios.post("{{ route('complaints.step-second') }}", formData, {
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

        function submitStep3() {
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

            axios.post("{{ route('complaints.step-third') }}", {
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
            const against_district_id = document.getElementById('against_district_id')?.value;

            let formData = new FormData();
            formData.append('firm_name', firm_name);
            formData.append('firm_address', firm_address);
            formData.append('proof_document', proof_document);
            formData.append('remarks', remarks);
            formData.append('gstin', gstin);
            formData.append('against_district_id', against_district_id);

            if(!firm_name) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter Firm Name!',
                    confirmButtonText: 'OK'
                });
            }

            if(!firm_address) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter Firm Address!',
                    confirmButtonText: 'OK'
                });
            }



            if(!gstin){
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter GSTIN!',
                    confirmButtonText: 'OK'
                });
            }

            if(!against_district_id) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Select District!',
                    confirmButtonText: 'OK'
                });
            }

            if(!remarks) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter Remarks!',
                    confirmButtonText: 'OK'
                });
            }


            if (!proof_document) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Please upload a document!',
                    confirmButtonText: 'OK'
                });
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(proof_document.type)) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Only JPG, PNG, or PDF files are allowed!',
                    confirmButtonText: 'OK'
                });
            }


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
                        window.location.href = "{{ route('user.dashboard') }}";
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
            const addressInput = document.querySelector('input[name="complainant_address"]');
            const phoneInput = document.querySelector('input[name="complainant_phone"]');
            const aadhaarInput = document.querySelector('input[name="complainant_aadhaar"]');
            const emailInput = document.querySelector('input[name="complainant_email"]');

            nameInput.addEventListener('input', () => {
                nameInput.value = nameInput.value.replace(/[^a-zA-Z\s]/g, '');
            });
            // addressInput.addEventListener('input', () => {
            //     addressInput.value = addressInput.value.replace(/[^a-zA-Z0-9\s]/g, '');
            // });
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
            $('#loader').removeClass("d-none");
            axios.get("{{ route('user.data') }}")
                .then(function(response) {
                    console.log(response.data.complaints.complaint_type);
                    $('input[name="complainant_name"]').val(response.data.complaints.complainant_name);
                    $('#complainant_address').val(response.data.complaints.complainant_address);
                    $('input[name="complainant_aadhaar"]').val(response.data.complaints.complainant_aadhaar);
                    $('input[name="complainant_email"]').val(response.data.complaints.complainant_email);
                    $('#complaint_type').val(response.data.complaints.complaint_type);
                    $('#complainant_dist_id').val(response.data.complaints.complainant_dist_id);
                    $('#complaint_type').val(response.data.complaints.complaint_type);
                    $('#fraudCheck').val(response.data.complaints.is_fraud_related);


                    $('#loader').addClass("d-none");
                    const existingImg = document.getElementById('existing-image');
                    existingImg.src = "{{ asset('storage') }}/" + response.data.complaints.upload_document;
                    existingImg.style.display = 'block';
                })
                .catch(function(error) {
                    $('#loader').addClass("d-none");
                    console.error('Error:', error.response);
                });
        })

        document.getElementById('document').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            const maxSize = 200 * 1024;

            if (!file) return; 

            // File type validation
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Only JPG, JPEG, or PNG files are allowed!',
                    confirmButtonText: 'OK'
                });
                e.target.value = ''; // Reset file input
                return;
            }

            // File size validation
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'File size must be less than 200 KB!',
                    confirmButtonText: 'OK'
                });
                e.target.value = ''; // Reset file input
                return;
            }
        });
    </script>


</body>

</html>
