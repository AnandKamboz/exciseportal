<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Complaint / Informer Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
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

        .card {
            max-width: 1270px;
            margin: 2rem auto;
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

        h4.card-title {
            color: #333;
            font-weight: 700;
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
            box-shadow: 0 0 8px rgba(255, 106, 0, 0.3);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .required::after {
            content: " *";
            color: red;
        }

        .step {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .step.active {
            display: block;
        }

        .btn-step {
            border-radius: 20px;
            font-weight: 600;
            padding: 0.6rem 2rem;
            background: #7386ff;
            border: none;
            color: #fff;
        }

        .btn-step:hover {
            background: #4b59ba;
        }

        .btn-success {
            border-radius: 20px;
        }

        .word-count {
            font-size: 0.9rem;
            color: #666;
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

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
</head>

<body>

    <!-- Loader -->
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

    <!-- Multi-Step Form -->
    <div class="container">
        <h6 class="text-danger mt-4 mb-3" style="font-weight: 700; font-size: 14px;">
            Note: All fields with <span class="text-danger">*</span> are mandatory.
        </h6>

        <div class="card">

            <div class="mb-4">
                <div class="progress" style="height: 10px;">
                    <div id="progressBar" class="progress-bar" style="width:33%"></div>
                </div>
            </div>

            <form id="multiStepForm" enctype="multipart/form-data" novalidate>

                <!-- STEP 1 -->
                <div class="step active" data-step="1">
                    <h5>Step 1 — Details of Informer </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Name</label>
                            {{-- <input id="informerName" name="informerName" placeholder="Enter your name" type="text"
                                class="form-control" required value="{{ $userData->complainant_name ?? '' }}"> --}}
                            <input id="informerName" name="informerName" type="text" class="form-control"
                                placeholder="Enter your name" required
                                value="{{ $userDataForNewApplication->complainant_name ?? ($userData->complainant_name ?? '') }}"
                                @if (!empty($userDataForNewApplication->complainant_name)) disabled @endif>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Phone Number</label>
                            <input id="informerPhone" name="informerPhone" type="tel" class="form-control"
                                value="{{ Auth::user()->mobile ?? '' }}" placeholder="10 digit mobile" required
                                disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            {{-- <input id="informerEmail" name="informerEmail" type="email" class="form-control"
                                placeholder="example@mail.com" value="{{ $userData->complainant_email ?? '' }}"> --}}
                            <input id="informerEmail" name="informerEmail" type="email" class="form-control"
                                placeholder="example@mail.com"
                                value="{{ $userDataForNewApplication->complainant_email ?? ($userData->complainant_email ?? '') }}"
                                @if (!empty($userDataForNewApplication->complainant_email)) disabled @endif>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Aadhar Number</label>
                            {{-- <input id="informerAadhar" name="informerAadhar" type="text" class="form-control"
                                maxlength="12" placeholder="Enter 12-digit Aadhar Number"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                value="{{ $userData->complainant_aadhar ?? '' }}"> --}}
                            <input id="informerAadhar" name="informerAadhar" type="text" class="form-control"
                                maxlength="12" placeholder="Enter 12-digit Aadhar Number"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                value="{{ $userDataForNewApplication->complainant_aadhar ?? ($userData->complainant_aadhar ?? '') }}"
                                @if (!empty($userDataForNewApplication->complainant_aadhar)) disabled @endif>

                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Address</label>
                        {{-- <textarea id="informerAddress" name="informerAddress" class="form-control" rows="3"
                            placeholder="House No., Street, City, District, State, Pincode" required>{{ $userData->complainant_address ?? '' }}
                        </textarea> --}}
                        <textarea id="informerAddress" name="informerAddress" class="form-control" rows="3"
                            placeholder="House No., Street, City, District, State, Pincode" required
                            @if (!empty($userDataForNewApplication->complainant_address)) disabled @endif>{{ $userDataForNewApplication->complainant_address ?? ($userData->complainant_address ?? '') }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="button" id="toStep2" class="btn btn-step">Next</button>
                    </div>
                </div>

                <!-- STEP 2 -->

                <div class="step" data-step="2">
                    <h5 class="fw-bold mb-0">Step 2 — Information related to</h5>
                    <div class="mb-3 mt-4">
                        <label class="form-label required">Select Tax to which information is related</label>
                        <select id="taxType" name="taxType" class="form-select" required>
                            <option value="">Select</option>
                            <option value="gst"
                                {{ isset($userData) && $userData->complaint_type === 'gst' ? 'selected' : '' }}>
                                Goods and Services Tax (GST)
                            </option>
                            <option value="excise"
                                {{ isset($userData) && $userData->complaint_type === 'excise' ? 'selected' : '' }}>
                                Excise
                            </option>
                            <option value="vat"
                                {{ isset($userData) && $userData->complaint_type === 'vat' ? 'selected' : '' }}>
                                Value Added Tax (VAT) / Central Sales Tax (CST)
                            </option>
                        </select>


                    </div>


                    <div class="d-flex justify-content-between">
                        <button type="button" id="backTo1" class="btn btn-secondary">Back</button>
                        <button type="button" id="toStep3" class="btn btn-step">Next</button>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="step" data-step="3">
                    <h5 id="step3Title">Step 3 — Offence / Evasion Details</h5>

                    <!-- GST Fields -->
                    <div id="gstFields" style="display:none;">
                        <!-- <div class="mb-3">
                            <label class="form-label required">Firm Name</label>
                            <input id="gstFirmName" name="gstFirmName" type="text" class="form-control">
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label">GSTIN</label>
                            <input id="gstGstin" name="gstGstin" type="text" class="form-control"
                                placeholder="15 character GSTIN">
                        </div> -->

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Firm Name</label>
                                <input id="gstFirmName" name="gstFirmName" type="text" class="form-control"
                                    placeholder="Enter firm name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">GSTIN</label>
                                <input id="gstGstin" name="gstGstin" type="text" class="form-control"
                                    placeholder="15 character GSTIN">
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label required">Firm Address</label>
                            <textarea id="gstFirmAddress" name="gstFirmAddress" class="form-control" rows="2"
                                placeholder="Building No., Street Name, Area/Locality, City, District, State, Pincode"></textarea>
                        </div>




                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Locality</label>
                                <input type="text" id="gstLocality" name="gstLocality" class="form-control"
                                    placeholder="Enter locality or area name">
                            </div>

                            {{-- <div class="col-md-6">
                                <label class="form-label required">District</label>
                                <input type="text" id="gstDistrict" name="gstDistrict" class="form-control"
                                    placeholder="Enter district name">
                            </div> --}}
                            <div class="col-md-6">
                                <label class="form-label required">District</label>
                                <select id="gstDistrict" name="gstDistrict" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label required">Description of Information <small
                                    class="text-muted">(Max 200 words)</small></label>
                            <textarea id="gstDescription" name="gstDescription" class="form-control" rows="3" maxlength="2000"
                                placeholder="Provide details about the activity"></textarea>
                            <small class="text-muted" id="descCount">0 / 200 words</small>
                        </div>

                        {{-- <div class="mb-3">
                            <label class="form-label required">Upload Proof (Max 5 files, each ≤1MB)</label>
                            <input id="gstProof" name="gstProof[]" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                class="form-control" multipleonchange="validateFiles(this)" multiple>
                        </div> --}}

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Upload Proof (Max 5 files, each ≤1MB)</label>
                                <input id="gstProof" name="gstProof[]" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="form-control" onchange="validateFiles(this)" multiple>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Vehicle Number (If available)</label>
                                <input id="gstVehicleNumber" name="gstVehicleNumber" type="text"
                                    class="form-control" placeholder="Enter Vehicle Number (e.g., HR26AB1234)"
                                    maxlength="10" oninput="this.value=this.value.toUpperCase()">
                            </div>
                        </div>
                    </div>

                    <script>
                        function validateFiles(input) {
                            const maxFiles = 5;
                            const maxSize = 1 * 1024 * 1024; // 1 MB
                            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                            const files = input.files;

                            if (files.length > maxFiles) {
                                Swal.fire('Error', `You can upload a maximum of ${maxFiles} files.`, 'error');
                                input.value = '';
                                return;
                            }

                            for (let i = 0; i < files.length; i++) {
                                const file = files[i];

                                if (!allowedTypes.includes(file.type)) {
                                    Swal.fire('Error', `File type not allowed: ${file.name}`, 'error');
                                    input.value = '';
                                    return;
                                }

                                if (file.size > maxSize) {
                                    Swal.fire('Error', `File size exceeds 1 MB: ${file.name}`, 'error');
                                    input.value = '';
                                    return;
                                }
                            }
                        }
                    </script>

                    <!-- VAT Fields -->
                    <!-- <div id="vatFields" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label required">Firm Name</label>
                            <input id="vatFirmName" name="vatFirmName" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TIN</label>
                            <input id="vatTin" name="vatTin" type="text" class="form-control"
                                placeholder="Tax Identification Number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Firm Address</label>
                            <textarea id="vatFirmAddress" name="vatFirmAddress" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Upload Proof</label>
                            <input id="vatProof" name="vatProof" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                class="form-control">
                        </div>
                    </div> -->

                    <div id="vatFields" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label required">Firm Name</label>
                            <input id="vatFirmName" name="vatFirmName" type="text" class="form-control"
                                placeholder="Enter firm name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">TIN</label>
                            <input id="vatTin" name="vatTin" type="text" class="form-control"
                                placeholder="Tax Identification Number">
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Firm Address</label>
                            <textarea id="vatFirmAddress" name="vatFirmAddress" class="form-control" rows="2"
                                placeholder="Building No., Street Name, Area/Locality, City, District, State, Pincode"></textarea>
                        </div>

                        <!-- ✅ New Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Locality</label>
                                <input type="text" id="vatLocality" name="vatLocality" class="form-control"
                                    placeholder="Enter locality or area name">
                            </div>
                            {{-- <div class="col-md-6">
                                <label class="form-label required">District</label>
                                <input type="text" id="vatDistrict" name="vatDistrict" class="form-control"
                                    placeholder="Enter district name">
                            </div> --}}

                            <div class="col-md-6">
                                <label class="form-label required">District</label>
                                <select id="vatDistrict" name="vatDistrict" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Description of Information <small
                                    class="text-muted">(Max 200 words)</small></label>
                            <textarea id="vatDescription" name="vatDescription" class="form-control" rows="3" maxlength="2000"
                                placeholder="Provide details about the activity"></textarea>
                            <small class="text-muted" id="vatDescCount">0 / 200 words</small>
                        </div>

                        <!-- <div class="mb-3">
                                <label class="form-label required">Upload Proof</label>
                                <input id="vatProof" name="vatProof" type="file" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
                            </div> -->

                        {{-- <div class="mb-3">
                            <label class="form-label required">Upload Proof (Max 5 files, each ≤1MB)</label>
                            <input id="vatProof" name="vatProof[]" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                class="form-control" multiple onchange="validateVatFiles(this)">
                        </div> --}}

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Upload Proof (Max 5 files, each ≤1MB)</label>
                                <input id="vatProof" name="vatProof[]" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="form-control" onchange="validateVatFiles(this)" multiple>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Vehicle Number (If available)</label>
                                <input id="vatVehicleNumber" name="vatVehicleNumber" type="text"
                                    class="form-control" placeholder="Enter Vehicle Number (e.g., HR26AB1234)"
                                    maxlength="10" oninput="this.value=this.value.toUpperCase()">
                            </div>
                        </div>
                    </div>
                    <script>
                        function validateVatFiles(input) {
                            const files = input.files;

                            // Check number of files
                            if (files.length > 5) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'You can upload a maximum of 5 files.'
                                });
                                input.value = ''; // clear selection
                                return false;
                            }

                            // Check file size (max 1MB)
                            for (let i = 0; i < files.length; i++) {
                                if (files[i].size > 1024 * 1024) { // 1 MB in bytes
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Each file must be less than 1 MB.'
                                    });
                                    input.value = ''; // clear selection
                                    return false;
                                }
                            }
                        }
                    </script>


                    <!-- Excise Fields -->
                    <div id="exciseFields" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label required">Name of Offender / Licensee</label>
                            <input id="exciseName" name="exciseName" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Licensee Description</label>
                            <input id="exciseDesc" name="exciseDesc" type="text" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Place of Offence</label>
                                <input id="excisePlace" name="excisePlace" type="text" class="form-control">
                            </div>


                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Time and Date of Offence</label>
                                <input id="exciseTime" name="exciseTime" type="datetime-local" class="form-control"
                                    placeholder="Select date & time">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Details of Offence (max 200 words)</label>
                            <textarea id="exciseDetails" name="exciseDetails" class="form-control" rows="5"></textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small id="wordCount" class="word-count">0 / 200 words</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">District</label>
                                <select id="exciseDistrict" name="exciseDistrict" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Vehicle Number (If available)</label>
                                <input id="exciseVehicleNumber" name="exciseVehicleNumber" type="text"
                                    class="form-control" placeholder="Enter Vehicle Number (e.g., HR26AB1234)"
                                    maxlength="10" oninput="this.value=this.value.toUpperCase()">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Upload Proof (Max 5 files, each ≤1MB)</label>
                            <input id="exciseProof" name="exciseProof[]" type="file" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf" multiple>
                            <small class="text-muted">Allowed formats: JPG, JPEG, PNG, PDF</small>
                            <ul id="proofList" class="mt-2"></ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" id="backTo2" class="btn btn-secondary">Back</button>
                        <button type="button" id="submitBtn" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        function showStep(step) {
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            const el = document.querySelector(`.step[data-step="${step}"]`);
            if (el) el.classList.add('active');
            document.getElementById('progressBar').style.width = (step / 3) * 100 + '%';
        }

        document.getElementById('toStep2').addEventListener('click', () => {
            $('#loader').removeClass('d-none');
            const name = informerName.value.trim();
            const phone = informerPhone.value.trim();
            const address = informerAddress.value.trim();
            const aadhar = informerAadhar.value.trim();
            const email = informerEmail.value.trim();

            if (!name) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Please enter informer name.', 'error');
            }

            if (!/^\d{10}$/.test(phone)) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Enter valid 10-digit phone.', 'error');
            }

            if (email !== "") {
                // Email pattern check
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailPattern.test(email)) {
                    $('#loader').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Email',
                        text: 'Please enter a valid email address (e.g., example@gmail.com)',
                    });
                    informerEmail.value = ""; // Clear invalid input
                    informerEmail.focus();
                    return false;
                }
            }

            if (!/^\d{12}$/.test(aadhar)) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Enter valid 12-digit Aadhar.', 'error');
            }

            if (!address) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Enter address.', 'error');
            }


            const formData = {
                informer_name: name,
                informer_aadhar: aadhar,
                informer_address: address,
                informer_email: email
            };

            // axios.post('/save-informer', formData)
            axios.post("{{ route('save.informer') }}", formData)
                .then(response => {
                    if (response.data.success) {
                        showStep(2);
                        $('#loader').addClass('d-none');
                    } else {
                        $('#loader').addClass('d-none');
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                });
        });


        document.getElementById('backTo1').onclick = () => showStep(1);

        document.getElementById('toStep3').onclick = () => {
            const complaintType = taxType.value; // renamed for clarity
            if (!complaintType) return Swal.fire('Error', 'Please select a complaint type.', 'error');

            $('#loader').removeClass('d-none');

            // Hide all sections first
            ['gstFields', 'vatFields', 'exciseFields'].forEach(id =>
                document.getElementById(id).style.display = 'none'
            );

            // 🔹 Send complaint type to backend
            // axios.post('/save-complaint-type', { complaint_type: complaintType })
            axios.post("{{ route('save.complaint.type') }}", {
                    complaint_type: complaintType
                })
                .then(response => {

                    $('#loader').addClass('d-none');
                    if (response.data.success) {
                        // console.log(response.data.complaint_type);
                        // document.getElementById('step3Title').innerHTML =
                        //     `Step 3 — Offence / Evasion Details (${response.data.complaint_type})`;
                        let type = response.data.complaint_type.toLowerCase();
                        if (type === 'gst') type = 'GST';
                        else if (type === 'vat') type = 'VAT/CST';
                        else if (type === 'excise') type = 'Excise';

                        // ✅ Update heading dynamically
                        document.getElementById('step3Title').innerHTML =
                            `Step 3 — Offence / Evasion Details (${type})`;

                        showStep(3);

                        // Show relevant form section
                        if (complaintType === 'gst') gstFields.style.display = 'block';
                        if (complaintType === 'vat') vatFields.style.display = 'block';
                        if (complaintType === 'excise') exciseFields.style.display = 'block';
                    } else {
                        Swal.fire('Error', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                });
        };

        document.getElementById('backTo2').onclick = () => showStep(2);

        exciseDetails?.addEventListener('input', () => {
            const count = exciseDetails.value.trim().split(/\s+/).filter(Boolean).length;
            wordCount.textContent = `${count} / 200 words`;
            if (count > 200)
                Swal.fire('Limit Exceeded', 'Maximum 200 words allowed.', 'warning');
        });

        document.getElementById('submitBtn').addEventListener('click', async () => {
            $('#loader').removeClass('d-none');
            const tax = taxType.value;

            if (tax == 'gst') {
                const firmName = gstFirmName.value.trim();
                const gstin = gstGstin.value.trim();
                const firmAddress = gstFirmAddress.value.trim();
                let gstProof = $('#gstProof')[0].files[0];
                const locality = gstLocality.value.trim();
                const district = gstDistrict.value.trim();
                const description = gstDescription.value.trim();
                const vehicleNumber = gstVehicleNumber.value.trim();
                if (!firmName) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Firm Name.', 'error');
                    return false;
                }

                if (gstin) {
                    if (gstin.length !== 15) {
                        $('#loader').addClass('d-none');
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid GSTIN',
                            text: 'GSTIN must be exactly 15 characters long.'
                        });
                        return false;
                    }
                }

                if (!firmAddress) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Firm Address.', 'error');
                    return false;
                }

                if (!gstProof) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please upload a proof document.', 'error');
                    return false;
                }

                let allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                let fileExtension = gstProof.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Invalid file type. Only PDF, JPG, JPEG, or PNG are allowed.', 'error');
                    $('#gstProof').val('');
                    return false;
                }

                if (gstProof.size > 1024 * 1024) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'File size must not exceed 1 MB.', 'error');
                    $('#gstProof').val('');
                    return false;
                }


                if (!locality) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Locality.', 'error');
                    return false;
                }

                // === District ===
                if (!district) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter District.', 'error');
                    return false;
                }

                // === Description of Information ===
                if (!description) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Description of Information.', 'error');
                    return false;
                }

            }




            if (tax == 'vat') {
                const firmName = vatFirmName.value.trim();
                const tin = vatTin.value.trim();
                const firmAddress = vatFirmAddress.value.trim();
                let vatProof = $('#vatProof')[0].files[0];
                const locality = vatLocality.value.trim();
                const district = vatDistrict.value.trim();
                const description = vatDescription.value.trim();
                const vehicleNumber = vatVehicleNumber.value.trim();



                if (!firmName) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Firm Name.', 'error');
                    return false;
                }

                if (!locality) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Locality.', 'error');
                    return false;
                }

                if (!district) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter District.', 'error');
                    return false;
                }

                if (!description) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Description of Information.', 'error');
                    return false;
                }

                if (!firmAddress) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Firm Address.', 'error');
                    return false;
                }

                if (!vatProof) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please upload your VAT/CST Proof document.', 'error');
                    return false;
                }

                let allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                let fileExtension = vatProof.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Invalid file type. Only PDF, JPG, JPEG, or PNG are allowed.', 'error');
                    $('#vatProof').val(''); // clear invalid file
                    return false;
                }

                // 🔹 File size check (max 1 MB)
                if (vatProof.size > 1024 * 1024) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'File size must not exceed 1 MB.', 'error');
                    $('#vatProof').val(''); // clear large file
                    return false;
                }
            }



            if (tax == 'excise') {
                const offender = exciseName.value.trim();
                const details = exciseDetails.value.trim();
                const vehicleNumber = exciseVehicleNumber.value.trim();

                if (!offender) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Name of Offender / Licensee.', 'error');
                    return false;
                }

                if (!details) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Details of Offence.', 'error');
                    return false;
                }

                if (details.split(/\s+/).length > 200) {
                    $('#loader').addClass('d-none');
                    return Swal.fire('Error', 'Details exceed 200 words.', 'error');
                }
            }

            const formData = new FormData(multiStepForm);
            try {
                // const res = await axios.post('/submit-complaint', formData);
                const res = await axios.post("{{ route('submit.complaint') }}", formData);

                if (res.data.success) {
                    $('#loader').addClass('d-none');
                    Swal.fire(
                        'Success',
                        `Your complaint has been submitted successfully!<br><b>Application ID:</b> ${res.data.application_id}`,
                        'success'
                    ).then(() => {
                        multiStepForm.reset();
                        window.location.href = "{{ url('user/dashboard') }}";
                    });
                } else {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', res.data.message, 'error');
                }
            } catch (err) {
                console.error(err);
                $('#loader').addClass('d-none');
                Swal.fire('Error', 'Submission failed. Try again.', 'error');
            }
        });
    </script>

    <script>
        document.getElementById('informerName').addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-z\s]/g, '');
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const taxType = document.getElementById('taxType');

            taxType.addEventListener('change', function() {
                // Hide all Step 3 sections
                ['gstFields', 'vatFields', 'exciseFields'].forEach(id => {
                    const el = document.getElementById(id);
                    el.style.display = 'none';
                });

                // Clear all input + textarea + file fields inside Step 3
                const step3 = document.querySelector('[data-step="3"]');
                step3.querySelectorAll('input, textarea').forEach(input => {
                    if (input.type === 'file') {
                        input.value = ''; // reset file
                    } else {
                        input.value = ''; // reset text, email, etc.
                    }
                });

                // Reset word count if visible
                const wordCount = document.getElementById('wordCount');
                if (wordCount) wordCount.textContent = '0 / 200 words';
            });
        });
    </script>

    <script>
        document.getElementById("gstGstin").addEventListener("input", function() {
            let value = this.value;
            value = value.replace(/[^A-Za-z0-9]/g, '');

            if (value.length > 15) {
                value = value.substring(0, 15);
            }

            this.value = value;
        });

        // Here
        document.getElementById("gstProof").addEventListener("change", function() {
            const file = this.files[0];
            if (!file) return;

            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            const maxSize = 1 * 1024 * 1024;

            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Only PDF, JPG, JPEG, and PNG files are allowed.',
                });
                this.value = '';
                return;
            }

            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must not exceed 1 MB.',
                });
                this.value = '';
                return;
            }
        });

        // HERE
        document.getElementById("gstFirmName").addEventListener("input", function() {
            const value = this.value;
            const cleaned = value.replace(/[^a-zA-Z0-9 .-]/g, '');

            if (value !== cleaned) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Characters',
                    text: 'Special characters are not allowed in Firm Name.',
                });
                this.value = cleaned;
            }
        });


        $('#gstDescription').on('input', function() {
            let text = $(this).val().trim();
            let words = text.split(/\s+/).filter(word => word.length > 0);
            $('#descCount').text(words.length + ' / 200 words');

            if (words.length > 200) {
                Swal.fire('Error', 'Description cannot exceed 200 words.', 'error');
                $(this).val(words.slice(0, 200).join(' ')); // truncate extra words
                $('#descCount').text('200 / 200 words');
            }
        });
    </script>

    <script>
        document.getElementById('exciseProof').addEventListener('change', function() {
            const files = this.files;
            const proofList = document.getElementById('proofList');
            proofList.innerHTML = ''; // Clear previous list

            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            const maxFiles = 5;
            const maxSize = 1 * 1024 * 1024; // 1MB

            if (files.length > maxFiles) {
                Swal.fire('Error', 'You can upload a maximum of 5 files only.', 'error');
                this.value = '';
                return;
            }

            for (let file of files) {
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire('Error', 'Only JPG, JPEG, PNG, or PDF files are allowed.', 'error');
                    this.value = '';
                    return;
                }
                if (file.size > maxSize) {
                    Swal.fire('Error', `${file.name} exceeds 1MB size limit.`, 'error');
                    this.value = '';
                    return;
                }

                // Display file names
                const li = document.createElement('li');
                li.textContent = file.name;
                proofList.appendChild(li);
            }
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</body>

</html>
