<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Complaint / Informer Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
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

        select.form-control {
            appearance: auto !important;
            -webkit-appearance: auto !important;
            -moz-appearance: auto !important;
            background-image: initial !important;
            background-position: right 10px center !important;
            background-repeat: no-repeat !important;
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
            <i>📞</i> 0172-4567890
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
                    <div id="progressBar" class="progress-bar" style="width:50%"></div>
                </div>
            </div>

            <form id="multiStepForm" enctype="multipart/form-data" novalidate>
                <!-- STEP 1 -->
                <div class="step active" data-step="1">
                    {{-- <h5>Step 1 — Details of Informer </h5> --}}
                    <h5>Step 1 — Please enter your details</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Name</label>
                            <input id="informerName" name="informerName" type="text" class="form-control"
                                placeholder="Enter your name" required
                                value="{{ $userDataForNewApplication->complainant_name ?? ($userData->complainant_name ?? '') }}"
                                @if (!empty($userDataForNewApplication->complainant_name)) disabled @endif>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input id="informerPhone" name="informerPhone" type="tel" class="form-control"
                                value="{{ Auth::user()->mobile ?? '' }}" placeholder="10 digit mobile" required
                                disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input id="informerEmail" name="informerEmail" type="email" class="form-control"
                                placeholder="example@mail.com"
                                value="{{ $userDataForNewApplication->complainant_email ?? ($userData->complainant_email ?? '') }}"
                                @if (!empty($userDataForNewApplication->complainant_email)) disabled @endif>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Aadhar Number</label>
                            <input id="informerAadhar" name="informerAadhar" type="text" class="form-control"
                                maxlength="12" placeholder="Enter 12-digit Aadhar Number"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                value="{{ $userDataForNewApplication->complainant_aadhar ?? ($userData->complainant_aadhar ?? '') }}"
                                @if (!empty($userDataForNewApplication->complainant_aadhar)) disabled @endif>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="my_state" class="form-label required">State</label>
                            <select id="my_state" name="my_state" class="form-control"
                                @if (isset($userDataForNewApplication)) disabled @endif>
                                <option value="">Select State</option>
                                @foreach ($indiaStates as $a)
                                    <option value="{{ $a->id }}"
                                        @if (
                                            (isset($userData) && intval($userData->complainant_state) == $a->id) ||
                                                (isset($userDataForNewApplication) && intval($userDataForNewApplication->complainant_state) == $a->id) ||
                                                (!isset($userData) && !isset($userDataForNewApplication) && $a->id == 8)) selected @endif>
                                        {{ $a->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="di" class="form-label required">District</label>
                            <select id="di" name="di" class="form-control"
                                @if (isset($userDataForNewApplication)) disabled @endif>
                                <option value="">Select District</option>
                                @if (isset($haryanaDistrictsList))
                                    @foreach ($haryanaDistrictsList as $d)
                                        <option value="{{ $d->id }}"
                                            @if (
                                                (isset($userData) && intval($userData->complainant_district) == $d->id) ||
                                                    (isset($userDataForNewApplication) && intval($userDataForNewApplication->complainant_district) == $d->id)) selected @endif>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>


                    {{-- <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Address 1</label>
                            <input type="text" name="address1" id="address1" class="form-control" value="" required
                                placeholder="House/Building No., Street/Locality, Landmark">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Address 2</label>
                            <input type="text" name="address2" id="address2" class="form-control" value="" required
                                placeholder="Village/Town, District, State, Pincode">
                        </div>
                    </div> --}}

                    {{-- <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Address 1</label>
                            <input type="text" name="address1" id="address1" class="form-control"
                                placeholder="House/Building No., Street/Locality, Landmark"
                                value="{{ $userDataForNewApplication->complainant_address1 ?? ($userData->complainant_address1 ?? '') }}"
                    @if (!empty($userDataForNewApplication->complainant_address1)) disabled @endif required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label required">Address 2</label>
                    <input type="text" name="address2" id="address2" class="form-control" placeholder="Village/Town, District, State, Pincode" value="{{ $userDataForNewApplication->complainant_address2 ?? ($userData->complainant_address2 ?? '') }}" @if (!empty($userDataForNewApplication->complainant_address2)) disabled @endif required>
                </div>
        </div> --}}

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Address 1</label>
                            <textarea name="address1" id="address1" class="form-control" rows="3"
                                placeholder="House/Building No., Street/Locality, Landmark" @if (!empty($userDataForNewApplication->complainant_address1)) disabled @endif
                                required>{{ $userDataForNewApplication->complainant_address1 ?? ($userData->complainant_address1 ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Address 2</label>
                            <textarea name="address2" id="address2" class="form-control" rows="3" placeholder="Village/Town, Pincode"
                                @if (!empty($userDataForNewApplication->complainant_address2)) disabled @endif required>{{ $userDataForNewApplication->complainant_address2 ?? ($userData->complainant_address2 ?? '') }}</textarea>
                        </div>
                    </div>


                    <div class="text-end">
                        <button type="button" id="toStep2" class="btn btn-step">Next</button>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="step" data-step="2">
                    <h5 class="fw-bold mb-0">Step 2 — Information related to</h5>
                    <div class="mb-3 mt-4">
                        <label class="form-label required">
                            Select the category,to which the information relates
                        </label>
                        <select id="taxType" name="taxType" class="form-select" required>
                            <option value="" selected disabled>
                                Select
                            </option>
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
                                Value Added Tax (VAT) & Central Sales Tax (CST)
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
                    {{-- <h5 id="step3Title">Step 3 — Offence / Evasion Details</h5> --}}
                    <h5 id="step3Title" class="mb-2">Step 2 — Details of Information (GST)</h5>




                    {{-- <div id="gstFields" style="display:none;"> --}}
                    <div id="gstFields" class="d-none">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Type of Information</label>
                                <select id="complaintType" name="complaintType" class="form-select" required>
                                    <option value="">Select Information</option>
                                    <option value="wrong_rate_of_tax">Wrong rate of tax</option>
                                    <option value="receipt_not_issued">Receipt not being issued</option>
                                    <option value="any_other">Any Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">
                                    Description of Information
                                    <small class="text-muted">(Max 150 words)</small>
                                </label>
                                <textarea id="gstDescription" name="gstDescription" class="form-control" rows="2"
                                    placeholder="Provide details about the activity"></textarea>
                                <small class="text-muted" id="descCount">0 / 150 words</small>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label required">
                                Location
                                <small class="text-muted"></small>
                            </label>
                            <textarea name="location" cols="2" id="location"
                                placeholder="Example: Near Matka Chowk, opposite Kali Mata Mandir"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">District</label>
                                <select id="district" name="district" class="form-control" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Pincode</label>
                                <input type="text" id="pincode" name="pincode" class="form-control"
                                    placeholder="Enter 6-digit Pincode" maxlength="6" pattern="[0-9]{6}" required>
                            </div>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

                        <script>
                            const pincodeInput = document.getElementById('pincode');
                            const districtSelect = document.getElementById('district');

                            pincodeInput.addEventListener('input', function() {
                                this.value = this.value.replace(/[^0-9]/g, '');

                                if (this.value.length === 6) {
                                    validatePincode(this.value);
                                }
                            });

                            function validatePincode(pincode) {
                                $('#loader').removeClass("d-none");
                                axios.get(`https://api.postalpincode.in/pincode/${pincode}`)
                                    .then(function(response) {
                                        const data = response.data[0];

                                        if (data.Status !== "Success" || !data.PostOffice || data.PostOffice.length === 0) {
                                            $('#loader').addClass("d-none");
                                            Swal.fire("Invalid Pincode", "Please enter a valid Indian Pincode.", "error");
                                            pincodeInput.value = "";
                                            return;
                                        }

                                        const apiDistrict = data.PostOffice[0].District.trim().toLowerCase();

                                        const selectedDistrict = districtSelect.value.trim().toLowerCase();
                                        const selectedDistrictName = districtSelect.options[districtSelect.selectedIndex].text;

                                        if (apiDistrict.toLowerCase() != selectedDistrictName.toLowerCase()) {
                                            Swal.fire(
                                                "District Mismatch",
                                                `This pincode does not belong to ${selectedDistrictName}.`,
                                                "error"
                                            );

                                            pincodeInput.value = "";
                                        }
                                        $('#loader').addClass("d-none");
                                    })
                                    .catch(function() {
                                        $('#loader').addClass("d-none");
                                        Swal.fire("Error", "Could not validate Pincode. Try again.", "error");
                                    });
                            }
                        </script>

                        {{-- <div class="mb-3"> --}}
                        <div class="row mt-3">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">
                                    Upload Picture (Max 5 picture, each ≤ 1MB, formats: JPG, JPEG, PNG)
                                </label>
                                <input id="gstProof" name="gstProof[]" type="file" accept=".jpg,.jpeg,.png"
                                    class="form-control" onchange="validateFiles(this)" multiple>
                            </div>
                        </div>
                    </div>

                    <div id="vatFields" style="display:none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Location</label>
                                <input type="text" id="vatLocality" name="vatLocality" class="form-control"
                                    placeholder="Enter location or area name">
                            </div>

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

                        <!-- City + Description -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">City</label>
                                <input type="text" id="vatCity" name="vatCity" class="form-control"
                                    placeholder="Enter City">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">
                                    Description of Information
                                    <small class="text-muted">(Max 150 words)</small>
                                </label>
                                <textarea id="vatDescription" name="vatDescription" class="form-control" rows="2"
                                    placeholder="Provide details about the activity"></textarea>
                                <small class="text-muted" id="vatDescCount">0 / 150 words</small>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <!-- Upload Proof -->
                            <div class="col-md-6">
                                <label class="form-label required">Upload Proof (Max 5 files, each ≤1MB)</label>
                                <input id="vatProof" name="vatProof[]" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="form-control" onchange="validateVatFiles(this)" multiple>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Who is involved? (Please provide details , if
                                    available)</label>
                                <select class="form-select" id="vatInvolvedType" name="vatInvolvedType">
                                    <option value="">-- Select --</option>
                                    <option value="firm">Firm</option>
                                    <option value="vehicle">Vehicle</option>
                                </select>
                            </div>
                        </div>

                        <!-- Firm Fields -->
                        <div id="vatFirmFields" style="display:none;">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Firm Name</label>
                                    <input id="vatFirmName" name="vatFirmName" type="text" class="form-control"
                                        placeholder="Enter firm name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">TIN</label>
                                    <input id="vatTin" name="vatTin" type="text" class="form-control"
                                        placeholder="Enter 11-digit TIN">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Firm Address</label>
                                <textarea id="vatFirmAddress" name="vatFirmAddress" class="form-control" rows="2"
                                    placeholder="Building No., Street Name, Area/Locality"></textarea>
                            </div>
                        </div>

                        <!-- Vehicle Fields -->
                        <div id="vatVehicleFields" style="display:none;">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Vehicle Number</label>
                                    <input id="vatVehicleNumber" name="vatVehicleNumber" type="text"
                                        class="form-control" placeholder="Enter Vehicle Number (e.g., HR26AB1234)"
                                        maxlength="10" oninput="this.value=this.value.toUpperCase()">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Person Name</label>
                                    <input id="vatPersonName" name="vatPersonName" type="text"
                                        class="form-control" placeholder="Enter person name">
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Excise Fields -->
                    <div id="exciseFields" style="display:none;">
                        <div class="row">
                            <!-- 1. Place / Location -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Place/Location of Offence</label>
                                <input id="excisePlace" name="excisePlace" type="text" class="form-control">
                            </div>

                            <!-- 2. District -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">District</label>
                                <select id="exciseDistrict" name="exciseDistrict" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- 3. New City Field -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">City</label>
                                <input id="exciseCity" name="exciseCity" type="text" class="form-control"
                                    placeholder="Enter City">
                            </div>

                            <!-- 4. Details of Offence -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Details of Offence (max 150 words)</label>
                                <textarea id="exciseDetails" name="exciseDetails" class="form-control" rows="1"></textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small id="wordCount" class="word-count">0 / 150 words</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- 5. Name of Offender -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Name of Offender / Licensee</label>
                                <input id="exciseName" name="exciseName" type="text" class="form-control">
                            </div>

                            <!-- 6. Licensee Description -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Licensee Description</label>
                                <input id="exciseDesc" name="exciseDesc" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <!-- 7. Time & Date -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Time and Date of Offence</label>
                                <input id="exciseTime" name="exciseTime" type="datetime-local" class="form-control">
                            </div>

                            <!-- 8. Vehicle Number -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vehicle Number (If available)</label>
                                <input id="exciseVehicleNumber" name="exciseVehicleNumber" type="text"
                                    class="form-control" placeholder="Enter Vehicle Number (e.g., HR26AB1234)"
                                    maxlength="10" oninput="this.value=this.value.toUpperCase()">
                            </div>
                        </div>

                        <!-- 9. Proof Upload -->
                        <div class="mb-3">
                            <label class="form-label required">Upload Proof (Max 5 files, each ≤1MB)</label>
                            <input id="exciseProof" name="exciseProof[]" type="file" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf" multiple>
                            <small class="text-muted">Allowed formats: JPG, JPEG, PNG, PDF</small>
                            <ul id="proofList" class="mt-2"></ul>
                        </div>

                    </div>


                    <div class="involved-section p-3 mt-4 mb-3"
                        style="background:#f8f9fa; border-left:4px solid #0d6efd; border-radius:8px;">

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Who is involved?
                                <span class="text-muted">(Please provide details , if available and relevant)</span>
                            </label>
                            <select class="form-select" name="involvedType" id="involvedType">
                                <option value="">-- Select --</option>
                                <option value="firm">Firm</option>
                                <option value="vehicle">Vehicle</option>
                            </select>
                        </div>

                        <div class="row">
                            <div id="firmFields" style="display:none;" class="mt-4">
                                <h6 class="fw-bold mb-3 text-primary">Firm Details</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Firm Name</label>
                                        <input id="gstFirmName" name="gstFirmName" type="text"
                                            class="form-control" placeholder="Enter firm name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">GSTIN</label>
                                        <input id="gstGstin" name="gstGstin" type="text" class="form-control"
                                            placeholder="15 character GSTIN">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Firm Address</label>
                                    <textarea id="gstFirmAddress" name="gstFirmAddress" class="form-control" rows="2"
                                        placeholder="Building No., Street Name , City , State, Pincode"></textarea>
                                </div>
                            </div>


                            <div id="vehicleFields" style="display:none;" class="mt-4">
                                <h6 class="fw-bold mb-3 text-primary">Vehicle Details</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Vehicle Number (if available and relevant)</label>
                                        <input id="gstVehicleNumber" name="gstVehicleNumber" type="text"
                                            class="form-control" placeholder="Enter Vehicle Number (e.g., HR26AB1234)"
                                            maxlength="10" oninput="this.value=this.value.toUpperCase()">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Person Name</label>
                                        <input id="gstPersonName" name="gstPersonName" type="text"
                                            class="form-control" placeholder="Name of person involved">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label required">Declaration</label>
                        <div class="form-check">
                            <input class="form-check-input declaration-checkbox" type="checkbox" name="declaration"
                                value="1">
                            <label class="form-check-label">
                                <ol class="mb-0">
                                    <li>I have carefully examined the contents of the information being submitted.
                                    </li>
                                    <li>The contents of the information being submitted are correct to the best of
                                        my
                                        knowledge and understanding.</li>
                                    <li>The information being provided is solely with a purpose to help curb evasion
                                        of
                                        tax/revenue.</li>
                                </ol>
                            </label>
                        </div>
                        <small class="text-danger d-none" id="declarationError">Please select the checkbox.</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" id="backTo2" class="btn btn-secondary">Back</button>
                        <button type="button" id="submitBtn" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- JS -->
    <script>
        // function showStep(step) {
        //     if (step === 2) {
        //     step = 3;
        // }
        //     document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
        //     const el = document.querySelector(`.step[data-step="${step}"]`);
        //     if (el) el.classList.add('active');
        //     document.getElementById('progressBar').style.width = (step / 3) * 100 + '%';
        // }


        function showStep(step) {
            if (step === 2) {
                step = 3;
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            $('#gstFields').removeClass("d-none");
            // Hide all steps
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            // Show correct step
            const el = document.querySelector(`.step[data-step="${step}"]`);
            if (el) el.classList.add('active');
            // Progress bar update
            document.getElementById('progressBar').style.width = (step / 2) * 100 + '%';
        }




        document.getElementById('toStep2').addEventListener('click', () => {
            $('#loader').removeClass('d-none');
            const name = informerName.value.trim();
            const phone = informerPhone.value.trim();
            const email = informerEmail.value.trim();
            const aadhar = informerAadhar.value.trim();
            let state = $("#my_state").val();
            let district = $("#di").val();
            let address1 = $('#address1').val().trim();
            let address2 = $('#address2').val().trim();



            if (!name) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Please enter informer name.', 'error');
            }

            if (!/^\d{10}$/.test(phone)) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Enter valid 10-digit phone.', 'error');
            }

            if (email !== "") {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailPattern.test(email)) {
                    $('#loader').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Email',
                        text: 'Please enter a valid email address (e.g., example@gmail.com)',
                    });
                    informerEmail.value = "";
                    informerEmail.focus();
                    return false;
                }
            }

            if (!/^\d{12}$/.test(aadhar)) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Enter valid 12-digit Aadhar.', 'error');
            }


            if (!state) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Please select a State.', 'error');
            }

            if (!district) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Please select a District.', 'error');
            }


            if (!address1) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Please enter Address 1.', 'error');
            }

            // Address 2 Blank Check
            if (!address2) {
                $('#loader').addClass('d-none');
                return Swal.fire('Error', 'Please enter Address 2.', 'error');
            }

            const formData = {
                informer_name: name,
                informer_phone: phone,
                informer_email: email,
                informer_aadhar: aadhar,
                informer_state: state,
                informer_district: district,
                informer_address1: address1,
                informer_address2: address2
            };

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
            document.querySelector('.declaration-checkbox').checked = false;
            const complaintType = taxType.value;
            if (!complaintType) return Swal.fire('Error', 'Please select a complaint type.', 'error');

            $('#loader').removeClass('d-none');

            ['gstFields', 'vatFields', 'exciseFields'].forEach(id =>
                document.getElementById(id).style.display = 'none'
            );

            axios.post("{{ route('save.complaint.type') }}", {
                    complaint_type: complaintType
                })
                .then(response => {

                    $('#loader').addClass('d-none');
                    if (response.data.success) {
                        let type = response.data.complaint_type.toLowerCase();
                        if (type === 'gst') type = 'GST';
                        else if (type === 'vat') type = 'VAT & CST';
                        else if (type === 'excise') type = 'Excise';

                        document.getElementById('step3Title').innerHTML =
                            `Step 2 — Details of Information (${type})`;

                        showStep(3);

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

        // document.getElementById('backTo2').onclick = () => showStep(2);
        // document.getElementById('backTo2').onclick = () => showStep(1);
        document.getElementById('backTo2').onclick = () => {
            showStep(1);

            // पेज को टॉप पर स्क्रॉल करें
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // smooth scroll के लिए
            });
        };



        exciseDetails?.addEventListener('input', () => {
            const count = exciseDetails.value.trim().split(/\s+/).filter(Boolean).length;
            wordCount.textContent = `${count} / 150 words`;
            if (count > 200)
                Swal.fire('Limit Exceeded', 'Maximum 150 words allowed.', 'warning');
        });

        document.getElementById('submitBtn').addEventListener('click', async () => {
            $('#loader').removeClass('d-none');
            const tax = taxType.value;

            if (tax == 'gst') {
                const complaintType = document.getElementById('complaintType').value.trim();
                const gstDescription = document.getElementById('gstDescription').value.trim();
                // const buildingNo = document.getElementById('buildingNo').value.trim();
                // const streetName = document.getElementById('streetName').value.trim();
                const pincode = document.getElementById('pincode').value.trim();
                let files = document.getElementById('gstProof').files;

                // ---------------------- VALIDATION ----------------------

                if (!complaintType) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please select Type of Complaint.', 'error');
                    return false;
                }

                if (!gstDescription) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Description of Information.', 'error');
                    return false;
                }

                // if (!buildingNo) {
                //     $('#loader').addClass('d-none');
                //     Swal.fire('Error', 'Please enter Building No.', 'error');
                //     return false;
                // }

                // if (!streetName) {
                //     $('#loader').addClass('d-none');
                //     Swal.fire('Error', 'Please enter Street Name.', 'error');
                //     return false;
                // }

                if (!pincode) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Pincode.', 'error');
                    return false;
                }

                // 6 digit pincode
                if (!/^[0-9]{6}$/.test(pincode)) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Pincode must be exactly 6 digits.', 'error');
                    return false;
                }

                // ---------------------- FILE VALIDATION ----------------------
                if (files.length > 0) {
                    let allowedExtensions = ['jpg', 'jpeg', 'png'];
                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];

                        let fileExtension = file.name.split('.').pop().toLowerCase();
                        if (!allowedExtensions.includes(fileExtension)) {
                            $('#loader').addClass('d-none');
                            Swal.fire('Error', 'Invalid file type. Only JPG, JPEG, or PNG are allowed.',
                                'error');
                            document.getElementById('gstProof').value = "";
                            return false;
                        }

                        if (file.size > 1024 * 1024) {
                            $('#loader').addClass('d-none');
                            Swal.fire('Error', 'File size must not exceed 1 MB.', 'error');
                            document.getElementById('gstProof').value = "";
                            return false;
                        }
                    }
                }
            }

            if (tax == 'vat') {
                const firmName = vatFirmName.value.trim();
                const tin = vatTin.value.trim();
                const firmAddress = vatFirmAddress.value.trim();
                let vatProof = $('#vatProof')[0].files[0];
                const locality = vatLocality.value.trim();
                const district = vatDistrict.value.trim();
                const mycity = $('#vatCity').val();
                vatCity = mycity.trim();

                const description = vatDescription.value.trim();
                const vehicleNumber = vatVehicleNumber.value.trim();

                // if (!firmName) {
                //     $('#loader').addClass('d-none');
                //     Swal.fire('Error', 'Please enter Firm Name.', 'error');
                //     return false;
                // }

                if (!locality) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Location.', 'error');
                    return false;
                }

                if (!district) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter District.', 'error');
                    return false;
                }

                if (!vatCity) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter District.', 'error');
                    return false;
                }

                if (!description) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'Please enter Description of Information.', 'error');
                    return false;
                }

                // if (!firmAddress) {
                //     $('#loader').addClass('d-none');
                //     Swal.fire('Error', 'Please enter Firm Address.', 'error');
                //     return false;
                // }

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
                    $('#vatProof').val('');
                    return false;
                }

                if (vatProof.size > 1024 * 1024) {
                    $('#loader').addClass('d-none');
                    Swal.fire('Error', 'File size must not exceed 1 MB.', 'error');
                    $('#vatProof').val('');
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
                    return Swal.fire('Error', 'Details exceed 150 words.', 'error');
                }
            }

            const declaration = document.querySelector('.declaration-checkbox');

            if (!declaration || declaration.checked === false) {
                $('#loader').addClass('d-none');

                Swal.fire({
                    icon: 'error',
                    title: 'Please confirm the declaration',
                    text: 'You must tick the declaration checkbox before submitting.'
                });

                return false;
            }

            $('#loader').addClass('d-none');
            const confirmSubmit = await Swal.fire({
                title: "Are you sure?",
                text: "Do you want to submit this information?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Submit",
                cancelButtonText: "Cancel"
            });
            $('#loader').removeClass('d-none');

            if (!confirmSubmit.isConfirmed) {
                $('#loader').addClass('d-none');
                return false;
            }


            const formData = new FormData(multiStepForm);
            try {
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
                let errorMessage = "Submission failed. Try again.";

                if (err.response && err.response.data && err.response.data.message) {
                    errorMessage = err.response.data.message;
                } else if (err.message) {
                    errorMessage = err.message;
                }

                Swal.fire('Error', errorMessage, 'error');
            }
        });

        document.getElementById('informerName').addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-z\s]/g, '');
        });

        document.addEventListener('DOMContentLoaded', () => {
            const taxType = document.getElementById('taxType');

            taxType.addEventListener('change', function() {
                ['gstFields', 'vatFields', 'exciseFields'].forEach(id => {
                    const el = document.getElementById(id);
                    el.style.display = 'none';
                });

                const step3 = document.querySelector('[data-step="3"]');
                step3.querySelectorAll('input, textarea').forEach(input => {
                    if (input.type === 'file') {
                        input.value = '';
                    } else {
                        input.value = '';
                    }
                });

                const wordCount = document.getElementById('wordCount');
                if (wordCount) wordCount.textContent = '0 / 150 words';
            });
        });

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
            $('#descCount').text(words.length + ' / 150 words');

            if (words.length > 200) {
                Swal.fire('Error', 'Description cannot exceed 150 words.', 'error');
                $(this).val(words.slice(0, 200).join(' '));
                $('#descCount').text('150 / 150 words');
            }
        });


        document.getElementById('exciseProof').addEventListener('change', function() {
            const files = this.files;
            const proofList = document.getElementById('proofList');
            proofList.innerHTML = '';

            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            const maxFiles = 5;
            const maxSize = 1 * 1024 * 1024;

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


                const li = document.createElement('li');
                li.textContent = file.name;
                proofList.appendChild(li);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cityInput = document.getElementById('informerCity');
            const districtInput = document.getElementById('informerDistrict');

            function allowOnlyLetters(input) {
                input.value = input.value.replace(/[^A-Za-z\s]/g, '');
            }

            // cityInput.addEventListener('input', function() {
            //     allowOnlyLetters(this);
            // });
        });


        const textarea = document.getElementById('gstDescription');
        const counter = document.getElementById('descCount');
        const MAX_WORDS = 100;
        const HARD_LIMIT = 150;

        textarea.addEventListener('input', () => {
            let words = textarea.value.trim().split(/\s+/).filter(w => w.length > 0);
            let wordCount = words.length;

            if (wordCount > HARD_LIMIT) {
                words = words.slice(0, HARD_LIMIT);
                textarea.value = words.join(' ');
                wordCount = HARD_LIMIT;
            }

            counter.textContent = `${Math.min(wordCount, MAX_WORDS)} / ${MAX_WORDS} words`;

            if (wordCount > MAX_WORDS) {
                textarea.value = words.slice(0, MAX_WORDS).join(' ');
                counter.textContent = `${MAX_WORDS} / ${MAX_WORDS} words`;
            }
        });

        const vatInput = document.getElementById('vatTin');
        const vatCounter = document.getElementById('vatTinCount');
        const MAX_LENGTH = 11;

        vatInput.addEventListener('input', () => {
            vatInput.value = vatInput.value.replace(/[^a-zA-Z0-9]/g, '');

            if (vatInput.value.length > MAX_LENGTH) {
                vatInput.value = vatInput.value.slice(0, MAX_LENGTH);
            }

            vatCounter.textContent = `${vatInput.value.length} / ${MAX_LENGTH} characters`;
        });

        function validateFiles(input) {
            const maxFiles = 5;
            const maxSize = 1 * 1024 * 1024;
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

        function validateVatFiles(input) {
            const files = input.files;
            if (files.length > 5) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'You can upload a maximum of 5 files.'
                });
                input.value = '';
                return false;
            }

            for (let i = 0; i < files.length; i++) {
                if (files[i].size > 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Each file must be less than 1 MB.'
                    });
                    input.value = '';
                    return false;
                }
            }
        }


        $(document).ready(function() {
            loadDistricts();

            function loadDistricts(stateId = '', userTriggered = false) {
                $('#loader').removeClass('d-none');

                $.ajax({
                    url: "{{ route('get-dist') }}",
                    type: "GET",
                    data: {
                        state_id: stateId,
                        userMobile: "{{ $userData->complainant_phone ?? '' }}"
                    },
                    success: function(res) {
                        $('#loader').addClass('d-none');

                        if (!userTriggered && res.selectedState) {
                            $('#my_state').val(res.selectedState);
                        }

                        $('#di').html('<option value="">Select District</option>');
                        res.districts.forEach(function(d) {
                            $('#di').append(`<option value="${d.id}">${d.name}</option>`);
                        });

                        if (res.selectedDistrict) {
                            $('#di').val(res.selectedDistrict);
                        }
                    }
                });
            }

            $('#my_state').on('change', function() {
                let stateId = $(this).val();
                $('#di').html('<option>Loading...</option>');
                loadDistricts(stateId, true);
            });
        });




        document.getElementById("involvedType").addEventListener("change", function() {
            const value = this.value;
            if (value === "firm") {
                firmFields.style.display = "block";
                vehicleFields.style.display = "none";
                clearInputs(vehicleFields);
            } else if (value === "vehicle") {
                firmFields.style.display = "none";
                vehicleFields.style.display = "block";
                clearInputs(firmFields);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {

            const involvedType = document.getElementById("involvedType");
            const firmFields = document.getElementById("firmFields");
            const vehicleFields = document.getElementById("vehicleFields");

            function clearInputs(container) {
                if (!container) return;
                container.querySelectorAll("input, textarea, select").forEach(el => {
                    el.value = "";
                });
            }

            involvedType.addEventListener("change", function() {
                const value = this.value;

                if (value === "firm") {

                    firmFields.style.display = "block";
                    vehicleFields.style.display = "none";
                    clearInputs(vehicleFields);

                } else if (value === "vehicle") {

                    firmFields.style.display = "none";
                    vehicleFields.style.display = "block";
                    clearInputs(firmFields);

                } else {
                    firmFields.style.display = "none";
                    vehicleFields.style.display = "none";

                    clearInputs(firmFields);
                    clearInputs(vehicleFields);
                }
            });
        });





        // document.addEventListener("DOMContentLoaded", function() {

        //     /* ---------------- GST Section ---------------- */
        //     const firmFields = document.getElementById("firmFields");
        //     const vehicleFields = document.getElementById("vehicleFields");

        //     function clearInputs(container) {
        //         if (!container) return;
        //         container.querySelectorAll("input, textarea").forEach(el => el.value = "");
        //     }

        //     document.querySelectorAll("input[name='involvedType']").forEach(radio => {
        //         radio.addEventListener("change", function() {

        //             if (this.value === "firm") {
        //                 if (firmFields) firmFields.style.display = "block";
        //                 if (vehicleFields) vehicleFields.style.display = "none";
        //                 clearInputs(vehicleFields);

        //             } else if (this.value === "vehicle") {
        //                 if (firmFields) firmFields.style.display = "none";
        //                 if (vehicleFields) vehicleFields.style.display = "block";
        //                 clearInputs(firmFields);
        //             }
        //         });
        //     });


        //     /* ---------------- VAT Section ---------------- */
        //     const vatFirmFields = document.getElementById("vatFirmFields");
        //     const vatVehicleFields = document.getElementById("vatVehicleFields");

        //     document.querySelectorAll("input[name='vatInvolvedType']").forEach(radio => {
        //         radio.addEventListener("change", function() {

        //             if (this.value === "firm") {

        //                 if (vatFirmFields) vatFirmFields.style.display = "block";
        //                 if (vatVehicleFields) vatVehicleFields.style.display = "none";

        //                 // clear vehicle fields
        //                 const veh1 = document.getElementById("vatVehicleNumber");
        //                 const veh2 = document.getElementById("vatPersonName");
        //                 if (veh1) veh1.value = "";
        //                 if (veh2) veh2.value = "";

        //             } else {

        //                 if (vatFirmFields) vatFirmFields.style.display = "none";
        //                 if (vatVehicleFields) vatVehicleFields.style.display = "block";

        //                 ["vatFirmName", "vatTin", "vatFirmAddress"].forEach(id => {
        //                     const el = document.getElementById(id);
        //                     if (el) el.value = "";
        //                 });
        //             }
        //         });
        //     });

        // });


        document.addEventListener("DOMContentLoaded", function() {

            const vatInvolvedType = document.getElementById("vatInvolvedType");

            const vatFirmFields = document.getElementById("vatFirmFields");
            const vatVehicleFields = document.getElementById("vatVehicleFields");

            function clearInputs(container) {
                if (!container) return;
                container.querySelectorAll("input, textarea, select").forEach(el => {
                    el.value = "";
                });
            }

            vatInvolvedType.addEventListener("change", function() {
                const value = this.value;

                if (value === "firm") {
                    vatFirmFields.style.display = "block";
                    vatVehicleFields.style.display = "none";
                    clearInputs(vatVehicleFields);

                } else if (value === "vehicle") {
                    vatFirmFields.style.display = "none";
                    vatVehicleFields.style.display = "block";
                    clearInputs(vatFirmFields);

                } else {
                    // empty selection
                    vatFirmFields.style.display = "none";
                    vatVehicleFields.style.display = "none";
                    clearInputs(vatFirmFields);
                    clearInputs(vatVehicleFields);
                }
            });

        });

        const pincode = document.getElementById('pincode');
        pincode.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>

</html>
