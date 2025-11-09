<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Multi-step Complaint / Informer Form</title>

    <!-- Bootstrap & SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-buttons {
            margin-top: 1rem;
        }

        .required::after {
            content: " *";
            color: #d00;
        }

        .word-count {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3">Complaint Submission</h4>

                <div class="mb-3">
                    <div class="progress" style="height: 10px;">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width:33%"></div>
                    </div>
                </div>

                <form id="multiStepForm" enctype="multipart/form-data" novalidate>

                    <!-- STEP 1 — Informer Details -->
                    <div class="step active" data-step="1">
                        <h5>Step 1 — Details of Informer</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Name</label>
                                <input id="informerName" name="informerName" type="text" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Phone Number</label>
                                <input id="informerPhone" name="informerPhone" type="tel" class="form-control"
                                    placeholder="10 digit mobile" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input id="informerEmail" name="informerEmail" type="email" class="form-control"
                                    placeholder="example@mail.com">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="informerAadhar" class="form-label">
                                    Aadhar Number <span class="text-danger">*</span>
                                </label>
                                <input id="informerAadhar" name="informerAadhar" type="text" class="form-control"
                                    placeholder="Enter 12-digit Aadhar Number" maxlength="12"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Address</label>
                            <textarea id="informerAddress" name="informerAddress" class="form-control" rows="3"
                                placeholder="House, Street, City, District, State, Pincode" required></textarea>
                        </div>

                        <div class="step-buttons text-end">
                            <button type="button" id="toStep2" class="btn btn-primary">Next</button>
                        </div>
                    </div>

                    <!-- STEP 2 — Information Related to Tax -->
                    <div class="step" data-step="2">
                        <h5>Step 2 — Information Related To</h5>
                        <div class="mb-3">
                            <label class="form-label required">Select tax to which information is related</label>
                            <select id="taxType" name="taxType" class="form-select" required>
                                <option value="">Select</option>
                                <option value="gst">Goods and Services Tax (GST)</option>
                                <option value="excise">Excise</option>
                                <option value="vat">Value Added Tax (VAT) / Central Sales Tax (CST)</option>
                            </select>
                        </div>

                        <div class="step-buttons d-flex justify-content-between">
                            <button type="button" id="backTo1" class="btn btn-secondary">Back</button>
                            <button type="button" id="toStep3" class="btn btn-primary">Next</button>
                        </div>
                    </div>

                    <!-- STEP 3 — Offence / Evasion Details -->
                    <div class="step" data-step="3">
                        <h5 id="step3Title">Step 3 — Offence / Evasion Details</h5>

                        <!-- GST Fields -->
                        <div id="gstFields" class="tax-fields" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label required">Firm Name</label>
                                <input id="gstFirmName" name="gstFirmName" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">GSTIN</label>
                                <input id="gstGstin" name="gstGstin" type="text" class="form-control"
                                    placeholder="15 character GSTIN">
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Firm Address</label>
                                <textarea id="gstFirmAddress" name="gstFirmAddress" class="form-control"
                                    rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload proof document</label>
                                <input id="gstProof" name="gstProof" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- VAT Fields -->
                        <div id="vatFields" class="tax-fields" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label required">Firm Name</label>
                                <input id="vatFirmName" name="vatFirmName" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">TIN</label>
                                <input id="vatTin" name="vatTin" type="text" class="form-control"
                                    placeholder="Tax Identification Number">
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Firm Address</label>
                                <textarea id="vatFirmAddress" name="vatFirmAddress" class="form-control"
                                    rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload proof document</label>
                                <input id="vatProof" name="vatProof" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- Excise Fields -->
                        <div id="exciseFields" class="tax-fields" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label required">Name of the offender / licensee</label>
                                <input id="exciseName" name="exciseName" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description of the licensee (if any)</label>
                                <input id="exciseDesc" name="exciseDesc" type="text" class="form-control">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Place of offence</label>
                                    <input id="excisePlace" name="excisePlace" type="text" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Time of offence</label>
                                    <input id="exciseTime" name="exciseTime" type="text" class="form-control"
                                        placeholder="e.g. 2025-11-06 21:00">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Details of offence / modus operandi (max 200
                                    words)</label>
                                <textarea id="exciseDetails" name="exciseDetails" class="form-control" rows="5"
                                    maxlength="1500"></textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="word-count" id="wordCount">0 / 200 words</small>
                                    <small class="text-muted">(approx)</small>
                                </div>
                            </div>
                        </div>

                        <div class="step-buttons d-flex justify-content-between">
                            <button type="button" id="backTo2" class="btn btn-secondary">Back</button>
                            <button type="button" id="submitBtn" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // === Helper: Show Step ===
        function showStep(n) {
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            const stepEl = document.querySelector('.step[data-step="' + n + '"]');
            if (stepEl) stepEl.classList.add('active');
            document.getElementById('progressBar').style.width = (n / 3) * 100 + '%';
        }

        // === Navigation ===
        document.getElementById('toStep2').addEventListener('click', () => {
            const name = document.getElementById('informerName').value.trim();
            const phone = document.getElementById('informerPhone').value.trim();
            const address = document.getElementById('informerAddress').value.trim();
            const aadhar = document.getElementById('informerAadhar').value.trim();

            if (!name) return Swal.fire({ icon: 'error', title: 'Name required', text: 'Please enter informer name.' });
            if (!phone || !/^\d{10}$/.test(phone)) return Swal.fire({ icon: 'error', title: 'Invalid phone', text: 'Please enter a valid 10-digit phone number.' });
            if (!address) return Swal.fire({ icon: 'error', title: 'Address required', text: 'Please enter informer address.' });
            if (!aadhar || !/^\d{12}$/.test(aadhar)) return Swal.fire({ icon: 'error', title: 'Invalid Aadhar', text: 'Please enter a valid 12-digit Aadhar number.' });

            showStep(2);
        });

        document.getElementById('backTo1').addEventListener('click', () => showStep(1));

        document.getElementById('toStep3').addEventListener('click', () => {
            const tax = document.getElementById('taxType').value;
            if (!tax) {
                Swal.fire({ icon: 'error', title: 'Select tax type', text: 'Please select the tax (GST / Excise / VAT / CST) related to this information.' });
                return;
            }

            document.querySelectorAll('.tax-fields').forEach(f => f.style.display = 'none');
            document.getElementById('step3Title').textContent =
                'Step 3 — ' + (tax === 'gst' ? 'Evasion Details (GST)' :
                    (tax === 'vat' || tax === 'cst' ? 'Evasion Details (VAT/CST)' :
                        (tax === 'excise' ? 'Offence Details (Excise)' : 'Details')));

            if (tax === 'gst') document.getElementById('gstFields').style.display = 'block';
            else if (tax === 'vat' || tax === 'cst') document.getElementById('vatFields').style.display = 'block';
            else if (tax === 'excise') document.getElementById('exciseFields').style.display = 'block';

            showStep(3);
        });

        document.getElementById('backTo2').addEventListener('click', () => showStep(2));

        // === Word Counter ===
        const exciseDetails = document.getElementById('exciseDetails');
        const wordCountEl = document.getElementById('wordCount');
        if (exciseDetails) {
            exciseDetails.addEventListener('input', () => {
                const words = exciseDetails.value.trim().split(/\s+/).filter(Boolean).length;
                wordCountEl.textContent = `${words} / 200 words`;
                if (words > 200) {
                    Swal.fire({ icon: 'warning', title: 'Word limit exceeded', text: 'Please limit the description to 200 words.' });
                }
            });
        }

        // === Submit via Axios ===
        document.getElementById('submitBtn').addEventListener('click', async () => {
            const tax = document.getElementById('taxType').value;

            if (tax === 'excise') {
                const offender = document.getElementById('exciseName').value.trim();
                const details = document.getElementById('exciseDetails').value.trim();
                const words = details.split(/\s+/).filter(Boolean).length;
                if (!offender || !details)
                    return Swal.fire({ icon: 'error', title: 'Missing fields', text: 'Please fill offender name and details of offence.' });
                if (words > 200)
                    return Swal.fire({ icon: 'error', title: 'Too long', text: 'Details exceed 200 words. Shorten it.' });
            }

            const confirm = await Swal.fire({
                title: 'Confirm submission',
                text: 'Are you sure you want to submit this information?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel'
            });

            if (!confirm.isConfirmed) return;

            const formData = new FormData(document.getElementById('multiStepForm'));

            try {
                const response = await axios.post('/submit-complaint', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (response.status === 200) {
                    Swal.fire({ icon: 'success', title: 'Submitted', text: 'Your information has been successfully submitted!' });
                    document.getElementById('multiStepForm').reset();
                    showStep(1);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Request Failed', text: error.response?.data?.message || 'Unable to submit data.' });
            }
        });

        // === Initialize ===
        showStep(1);
    </script>
</body>

</html>
