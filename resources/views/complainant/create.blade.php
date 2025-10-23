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
              
                <h5>Type of Complaint</h5>
                <div class="mb-3">
                    <label for="complaint_type" class="form-label">
                        Complaint Category <span class="text-danger">*</span>
                    </label>
                    <select name="complaint_type" id="complaint_type" class="form-select" required>
                        <option value="" selected disabled>Select Complaint Type</option>
                        <option value="vat">Value Added Tax (VAT)</option>
                        <option value="gst">Goods & Services Tax (GST)</option>
                        <option value="excise">Excise/ Central Sales Tax (CST)</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-step" onclick="nextStep()">Next</button>
                </div>
            </div>
          
























            <div class="step" id="step2">
                <h5>Details of Complainant / Informer</h5>
                
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

                <div class="row">
                    <div class="col-half mb-3">
                        <label for="pin_code" class="form-label">Pin Code <span class="text-danger">*</span></label>
                        <input type="text" name="pin_code" id="pin_code" class="form-control"  
                            placeholder="Enter 6-digit Pin Code" required 
                            oninput="handlePinInput(this)">
                        <small class="text-muted">Only 6 digits allowed</small>
                    </div>
                </div>
                <!-- Pin Code -->

                <div class="row d-none" id="locationFields">
                    <div class="col-half mb-3">
                        <label for="complainant_district" class="form-label">
                            District <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="complainant_district" name="complainant_district"  class="form-control" readonly>
                    </div>
                    <div class="col-half mb-3">
                        <label for="complainant_state" class="form-label">
                            State <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="complainant_state" name="complainant_state" class="form-control" readonly>
                    </div>
                </div>


                <!-- Dist And State -->



                <div class="row">
                      <div class="">
                        <label for="complainant_address" class="form-label">
                            Address <span class="text-danger">*</span>
                        </label>
                        <textarea name="complainant_address" id="complainant_address" 
                            class="form-control mb-3" placeholder="Enter your Address" rows="3" required></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-half mb-3">
                        <label for="bank_account" class="form-label">Bank Account No. *</label>
                        <input type="text" id="bank_account" name="bank_account" class="form-control"
                        placeholder="Enter Bank Account No."
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,16);">
                        <small class="text-muted">8 to 16 digits</small>
                    </div>

                    <!-- Confirm Bank Account No -->
                    <div class="col-half mb-3">
                        <label for="confirm_bank_account" class="form-label">Confirm Bank Account No. *</label>
                        <input type="text" id="confirm_bank_account" name="confirm_bank_account" class="form-control"
                        placeholder="Re-enter Bank Account No."
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,16);">
                    </div>
                    </div>

                    <div class="row">
                    <!-- Bank Name -->
                    <div class="col-half mb-3">
                        <label for="bank_name" class="form-label">Bank Name *</label>
                        <input type="text" id="bank_name" name="bank_name" class="form-control"
                        placeholder="Enter Bank Name"
                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '');">
                    </div>

                    <!-- IFSC Code -->
                    <div class="col-half mb-3">
                        <label for="ifsc_code" class="form-label">IFSC Code *</label>
                        <input type="text" id="ifsc_code" name="ifsc_code" class="form-control"
                        placeholder="Enter IFSC Code"
                        oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0,11);">
                        <small class="text-muted">Max 11 characters</small>
                    </div>
                    </div>

                    <div class="row">
                    <!-- Bank Branch Address -->
                    <div class="mb-3">
                        <label for="bank_branch_address" class="form-label">Bank Branch Address *</label>
                        <textarea id="bank_branch_address" name="bank_branch_address" class="form-control" rows="3"
                        placeholder="Enter Bank Branch Address"
                        oninput="this.value = this.value.replace(/[^A-Za-z0-9 ,.-/]/g, '');"></textarea>
                    </div>
                </div>

                <div class="mt-2">
                    <label for="document_upload" class="form-label">
                        Upload Document (ID Proof)<span class="text-danger">*</span>
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
            

            
            <div class="step" id="step4">
                <h5>Details of Suspect</h5>
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

                    <div class="col-half">
                        <div class="mb-3">
                            <label for="gstin" class="form-label">
                                GSTIN <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="gstin" id="gstin" 
                                class="form-control" placeholder="Enter GSTIN">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-half mb-3">
                        <label for="address_detail" class="form-label">
                            Firm Address <span class="text-danger">*</span>
                        </label>
                        <textarea name="address_detail" id="address_detail" 
                            class="form-control" rows="3" placeholder="Enter Address"
                            oninput="sanitizeAddress(this)"></textarea>
                    </div>
                </div>


               
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

                    <div class="mb-2">
                        <label for="estimate_tax_amount" class="form-label">
                            Estimated amount of Tax Evasion (in Rupees) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="estimate_tax_amount" id="estimate_tax_amount" 
                            class="form-control" placeholder="Enter estimated tax amount" min="0" step="any">
                    </div>
                </div>


                <div class="row">
                      <div class="col-half">
                         <div class="mb-3">
                            <label for="document" class="form-label">
                                Upload Proof Document <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="document" id="document" 
                                class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
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
            if(category != 'gst' && currentStep === 4){
                $('#fraudCheck').val('');
                currentStep -= 2
                showStep(currentStep);
            }else{
                currentStep--;
                showStep(currentStep);
            }  
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


          

            const pin_code = document.querySelector('input[name="pin_code"]').value.trim();
            const complainant_district = document.querySelector('input[name="complainant_district"]').value.trim(); // from API
            const complainant_state = document.querySelector('input[name="complainant_state"]').value.trim(); // from API
            const bank_account = document.querySelector('input[name="bank_account"]').value.trim();
            const confirm_bank_account = document.querySelector('input[name="confirm_bank_account"]').value.trim();
            const bank_name = document.querySelector('input[name="bank_name"]').value.trim();
            const ifsc_code = document.querySelector('input[name="ifsc_code"]').value.trim();
            const bank_branch_address = document.querySelector('textarea[name="bank_branch_address"]').value.trim();



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

            // if(!complainant_dist_id) {
            //     $('#loader').addClass('d-none');
            //     return Swal.fire({
            //         icon: 'warning',
            //         title: 'Error',
            //         text: 'Select your District!',
            //         confirmButtonText: 'OK'
            //     });
            // }

            if (!complainant_address) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter your address!',
                    confirmButtonText: 'OK'
                });
            }


            // New Field 

            // Pin Code
            if (!pin_code || pin_code.length !== 6) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter a valid 6-digit Pin Code!',
                    confirmButtonText: 'OK'
                });
            }

            // District (from API)
            if (!complainant_district) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'District not found for this Pin Code!',
                    confirmButtonText: 'OK'
                });
            }

            // State (from API)
            if (!complainant_state) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'State not found for this Pin Code!',
                    confirmButtonText: 'OK'
                });
            }

            // Bank Account
            if (!bank_account || bank_account.length < 8 || bank_account.length > 16) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter a valid Bank Account number (8-16 digits)!',
                    confirmButtonText: 'OK'
                });
            }

            // Confirm Bank Account
            if (!confirm_bank_account || confirm_bank_account !== bank_account) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Bank Account numbers do not match!',
                    confirmButtonText: 'OK'
                });
            }

            // Bank Name
            if (!bank_name) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter Bank Name!',
                    confirmButtonText: 'OK'
                });
            }

            // IFSC Code
            if (!ifsc_code) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter IFSC Code!',
                    confirmButtonText: 'OK'
                });
            }

            // Bank Branch Address
            if (!bank_branch_address) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter Bank Branch Address!',
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
                    $('#upload_document').val(''); 
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
                    $('#upload_document').val(''); 
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


            formData.append('pin_code', pin_code);
            formData.append('complainant_district', complainant_district);
            formData.append('complainant_state', complainant_state);
            formData.append('bank_account', bank_account);
            formData.append('confirm_bank_account', confirm_bank_account);
            formData.append('bank_name', bank_name);
            formData.append('ifsc_code', ifsc_code);
            formData.append('bank_branch_address', bank_branch_address);

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
            const firm_address = document.querySelector('[name="address_detail"]').value.trim();
            const proof_document = document.querySelector('input[name="document"]').files[0];
            const remarks = document.querySelector('textarea[name="remarks"]').value.trim();
            const gstin = document.getElementById('gstin').value;
            const against_district_id = document.getElementById('against_district_id')?.value;
            const estimate_tax_amount = document.getElementById('estimate_tax_amount').value;

            let formData = new FormData();
            formData.append('firm_name', firm_name);
            formData.append('firm_address', firm_address);
            formData.append('proof_document', proof_document);
            formData.append('remarks', remarks);
            formData.append('gstin', gstin);
            formData.append('against_district_id', against_district_id);
            formData.append('estimate_tax_amount', estimate_tax_amount);

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

            if(!estimate_tax_amount || estimate_tax_amount <= 0) {
                $('#loader').addClass('d-none');
                return Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Enter valid Estimate Amount of Tax!',
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

                    // here 
                    $('input[name="pin_code"]').val(response.data.complaints.pin_code);
                    $('input[name="complainant_district"]').val(response.data.complaints.complainant_district);
                    $('input[name="complainant_state"]').val(response.data.complaints.complainant_state);
                    $('input[name="bank_account"]').val(response.data.complaints.bank_account);
                    $('input[name="confirm_bank_account"]').val(response.data.complaints.confirm_bank_account);
                    $('input[name="bank_name"]').val(response.data.complaints.bank_name);
                    $('input[name="ifsc_code"]').val(response.data.complaints.ifsc_code);
                    $('textarea[name="bank_branch_address"]').val(response.data.complaints.bank_branch_address);

                    if(response.data.complaints.complainant_district && response.data.complaints.complainant_state) {
                        $('#locationFields').removeClass('d-none');
                    } else {
                        $('#locationFields').addClass('d-none');
                    }


                    $('#loader').addClass("d-none");
                    const existingImg = document.getElementById('existing-image');
                    existingImg.src = "{{ asset('storage') }}/" + response.data.complaints.upload_document;
                    existingImg.style.display = 'block';
                })
                .catch(function(error) {
                    $('#loader').addClass("d-none");
                    // console.error('Error:', error.response);
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
   
        function handlePinInput(input) {
            input.value = input.value.replace(/[^0-9]/g, '').slice(0, 6);

            if(input.value.length === 6){
                fetchLocation(input.value);
            } else {
                document.getElementById('locationFields').classList.add('d-none');
                document.getElementById('complainant_district').value = '';
                document.getElementById('complainant_state').value = '';
            }
        }

        function fetchLocation(pincode) {
                $('#loader').removeClass('d-none');
                fetch(`https://api.postalpincode.in/pincode/${pincode}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data[0].Status === "Success" && data[0].PostOffice.length > 0){
                            document.getElementById('locationFields').classList.remove('d-none');
                            document.getElementById('complainant_district').value = data[0].PostOffice[0].District;
                            document.getElementById('complainant_state').value = data[0].PostOffice[0].State;
                              $('#loader').addClass('d-none');
                        } else {
                            document.getElementById('locationFields').classList.add('d-none');
                            document.getElementById('complainant_district').value = '';
                            document.getElementById('complainant_state').value = '';
                            $('#loader').addClass('d-none');
                        }
                    })
                    .catch(err => console.error(err));
        }
</script>


</body>

</html>
