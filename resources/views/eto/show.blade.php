@extends('eto.layouts.dashboard')

@section('title', 'Information Details')
@section('page_title', 'Information Details')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>


    @php
        $entityState = 'Haryana';
        $entityDistrict = null;

        if ($complain->complainant_district) {
            $entityDistrict = \App\Models\District::where('id', $complain->district_id)->value('name');
        }
    @endphp

    <style>
        .section-head {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #2c3e50;
        }

        .info-card {
            background: #fff;
            border: 1px solid #e4e4e4;
            padding: 14px;
            border-radius: 6px;
            transition: .2s;
        }

        .info-card:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.10);
        }

        .label-text {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .value-text {
            font-size: 15px;
            font-weight: 500;
            color: #111;
        }

        .btn-back {
            background: #e74c3c;
            border: none;
        }

        .btn-back:hover {
            background: #c0392b;
        }

        .required:after {
            content: " *";
            color: red;
            font-weight: bold;
        }

        .radio-wrap {
            display: flex;
            gap: 15px;
            padding: 10px;
            background: #f3f7ff;
            border-radius: 6px;
        }
    </style>

    <div class="container-fluid">

        <div class="card p-4 mb-4 shadow-sm border-0" style="background:#fafafa;">


            <div class="section-head text-center">Information Details</div>

            {{-- ========== INFORMER DETAILS ========== --}}
            <div class="section-head mt-3">Informer Details</div>

            <div class="row g-3">

                {{-- Application ID --}}
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label-text">Application ID</div>
                        <div class="value-text">{{ $complain->application_id }}</div>
                    </div>
                </div>

                {{-- Date of Information --}}
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label-text">Date of Information</div>
                        <div class="value-text">{{ $complain->created_at->format('d-m-Y') }}</div>
                    </div>
                </div>

            </div>

            <hr>

            {{-- ========== ENTITY DETAILS ========== --}}
            <div class="section-head">Entity Details</div>

            <div class="row g-3">

                {{-- Type of Complaint --}}
                @if ($complain->type_of_complaint)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Type of Information</div>
                            <div class="value-text">
                                {{ ucwords(str_replace('_', ' ', $complain->type_of_complaint)) }}
                            </div>

                        </div>
                    </div>
                @endif

                {{-- GST Description --}}
                @if ($complain->gst_description)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Brief Information / Details</div>
                            <div class="value-text">{{ $complain->gst_description }}</div>
                        </div>
                    </div>
                @endif

                {{-- District --}}
                @if ($complain->district_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">District</div>
                            <div class="value-text">{{ $complain->district_name }}</div>
                        </div>
                    </div>
                @endif

                {{-- Pincode --}}
                @if ($complain->pincode)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Pincode</div>
                            <div class="value-text">{{ $complain->pincode }}</div>
                        </div>
                    </div>
                @endif

                {{-- Involved Type --}}
                @if ($complain->involved_type)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Involved Type</div>
                            <div class="value-text">{{ $complain->involved_type }}</div>
                        </div>
                    </div>
                @endif

                {{-- Firm Name --}}
                @if ($complain->gst_firm_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm Name</div>
                            <div class="value-text">{{ $complain->gst_firm_name }}</div>
                        </div>
                    </div>
                @endif

                {{-- GSTIN --}}
                @if ($complain->gst_gstin)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">GSTIN</div>
                            <div class="value-text">{{ $complain->gst_gstin }}</div>
                        </div>
                    </div>
                @endif

                {{-- Firm Address --}}
                @if ($complain->gst_firm_address)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm Address</div>
                            <div class="value-text">{{ $complain->gst_firm_address }}</div>
                        </div>
                    </div>
                @endif

                {{-- Location --}}
                @if ($complain->location)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Location</div>
                            <div class="value-text">{{ $complain->location }}</div>
                        </div>
                    </div>
                @endif

                {{-- Address Line 2 --}}
                @if ($complain->gst_address2)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Address Line 2</div>
                            <div class="value-text">{{ $complain->gst_address2 }}</div>
                        </div>
                    </div>
                @endif

                {{-- Locality --}}
                @if ($complain->gst_locality)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Locality</div>
                            <div class="value-text">{{ $complain->gst_locality }}</div>
                        </div>
                    </div>
                @endif

                {{-- City --}}
                @if ($complain->gst_city)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">City</div>
                            <div class="value-text">{{ $complain->gst_city }}</div>
                        </div>
                    </div>
                @endif

                {{-- Vehicle Number --}}
                @if ($complain->gst_vehicle_number)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Vehicle Number</div>
                            <div class="value-text">{{ $complain->gst_vehicle_number }}</div>
                        </div>
                    </div>
                @endif

                {{-- VAT Firm Name --}}
                @if ($complain->vat_firm_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">VAT Firm Name</div>
                            <div class="value-text">{{ $complain->vat_firm_name }}</div>
                        </div>
                    </div>
                @endif

                {{-- VAT TIN --}}
                @if ($complain->vat_tin)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">VAT TIN</div>
                            <div class="value-text">{{ $complain->vat_tin }}</div>
                        </div>
                    </div>
                @endif

                {{-- VAT Person --}}
                @if ($complain->vat_person_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">VAT Person Name</div>
                            <div class="value-text">{{ $complain->vat_person_name }}</div>
                        </div>
                    </div>
                @endif

                {{-- VAT Description --}}
                @if ($complain->vat_description)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">VAT Description</div>
                            <div class="value-text">{{ $complain->vat_description }}</div>
                        </div>
                    </div>
                @endif

                {{-- Excise Details --}}
                @if ($complain->excise_desc)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Excise Description</div>
                            <div class="value-text">{{ $complain->excise_desc }}</div>
                        </div>
                    </div>
                @endif

                {{-- Here --}}
                @if ($complain->missing_gst_number)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Missing Gst Number</div>
                            <div class="value-text">{{ $complain->missing_gst_number }}</div>
                        </div>
                    </div>
                @endif

                @if ($complain->missing_firm_location)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Missing firm name</div>
                            <div class="value-text">{{ $complain->missing_firm_location }}</div>
                        </div>
                    </div>
                @endif

                @if ($complain->missing_address)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Missing firm address</div>
                            <div class="value-text">{{ $complain->missing_address }}</div>
                        </div>
                    </div>
                @endif

                @if ($complain->missing_info_submitted_at)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Missing Information Submission Time</div>
                            <div class="value-text">
                                {{ \Carbon\Carbon::parse($complain->missing_info_submitted_at)->format('Y-m-d') }}</div>
                        </div>
                    </div>
                @endif


                {{-- Here --}}

                @if ($complain->eto_missing_gst_number)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Missing GST Number</div>
                            <div class="value-text">
                                {{ $complain->eto_missing_gst_number }}
                            </div>
                        </div>
                    </div>
                @endif

                @if ($complain->eto_missing_firm_location)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm location</div>
                            <div class="value-text">
                                {{ $complain->eto_missing_firm_location }}
                            </div>
                        </div>
                    </div>
                @endif

                @if ($complain->eto_missing_address)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm Address</div>
                            <div class="value-text">
                                {{ $complain->eto_missing_address }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ========== DOCUMENTS WITH LIGHTBOX ========== --}}
            @if ($complain->gst_proof || $complain->vat_proof || $complain->excise_proof)
                <hr>
                <div class="section-head">Attached Documents</div>
                <div class="row g-3">
                    {{-- GST, VAT, EXCISE LOOP --}}
                    @foreach (['gst_proof' => 'GST', 'vat_proof' => 'VAT', 'excise_proof' => 'Excise'] as $key => $label)
                        @if ($complain->$key)
                            @php $docs = json_decode($complain->$key, true); @endphp

                            @foreach ($docs as $file)
                                @php
                                    $path = asset('storage/complaints/' . $complain->application_id . '/' . $file);
                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                @endphp


                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <div class="col-md-4">
                                        <a href="{{ $path }}" data-lightbox="{{ $label }}-docs"
                                            data-title="{{ $label }} Document">
                                            <img src="{{ $path }}" class="img-fluid rounded"
                                                style="max-height:150px;object-fit:cover;">
                                        </a>
                                    </div>

                                    {{-- If PDF → open normally --}}
                                @elseif($ext === 'pdf')
                                    <div class="col-md-4">
                                        <a href="{{ $path }}" target="_blank"
                                            class="btn btn-outline-primary w-100">
                                            View {{ $label }} PDF
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @endif
            <hr>

            <div class="container mt-4">
                @if ($etoActions->count())
                    <div class="card shadow-lg border-0 mb-4" style="border-left:5px solid #28a745;">
                        <div class="p-3 bg-success text-white fw-semibold">
                            ETO Action Submitted
                        </div>

                        <div class="p-4">
                            @foreach ($etoActions as $action)
                                <div class="mb-3">
                                    <b>Action:</b> {{ ucfirst(str_replace('_', ' ', $action->action)) }} <br>

                                    @if ($action->reason)
                                        <b>Reason:</b> {{ ucfirst(str_replace('_', ' ', $action->reason)) }} <br>
                                    @endif

                                    @if ($action->missing_info)
                                        <b>Missing Info:</b>
                                        {{ ucfirst(str_replace('_', ' ', $action->missing_info)) }} <br>
                                    @endif

                                    <b>Status:</b> {{ ucfirst($action->status) }} <br>

                                    <b>Remarks:</b> {{ $action->remarks }}

                                    @if ($action->report_file)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/complainant/' . $action->application_id . '/' . $action->report_file) }}"
                                                target="_blank" class="btn btn-sm btn-primary">
                                                View Uploaded Report
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    $showForm = false;
                    $lastAction = $etoActions->last();

                    if ($complain->eto_rise_issue == 1) {
                        $showForm = false;
                    } elseif ($etoActions->count() === 0) {
                        $showForm = true;
                    } elseif (
                        $lastAction &&
                        $lastAction->action === 'non_actionable' &&
                        $lastAction->reason === 'information_incomplete' &&
                        $infoIncompleteCount < 2 &&
                        $complain->eto_rise_issue == 0
                    ) {
                        $showForm = true;
                    }
                @endphp


                @if ($showForm)
                    <div class="card shadow-lg border-0" style="border-left:5px solid #0a3d62;">
                        <div class="p-3 text-white fw-semibold" style="background:#0a3d62;">
                            Proposed Action (ETO)
                        </div>

                        <div class="p-4">
                            <form method="POST" action="{{ route('eto.action.store', $complain->secure_id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="fw-semibold">
                                        <input type="radio" name="proposed_action" value="uploaded_report">
                                        Upload Report
                                    </label>

                                    <label class="fw-semibold ms-4">
                                        <input type="radio" name="proposed_action" value="non_actionable">
                                        Non Actionable
                                    </label>
                                </div>

                                <div id="uploadBox" style="display:none;">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="fw-semibold required">Upload Document</label>
                                            <input type="file" name="upload_file" class="form-control"
                                                accept=".jpg,.jpeg,.png" onchange="validateUploadFile(this)">
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="fw-semibold required">Remarks</label>
                                            <textarea name="remarks_upload" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="text-end mt-3">
                                        <button class="btn btn-success">Send to HQ</button>
                                    </div>
                                </div>

                                <div id="nonBox" style="display:none;">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="fw-semibold required">Select Reason</label>
                                            <select name="reason" id="nonReason" class="form-select">
                                                <option value="">Select</option>
                                                <option value="false_information">False Information</option>
                                                <option value="information_incomplete">Information Incomplete</option>
                                                <option value="any_other">Any Other</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-6" id="missingBox" style="display:none;">
                                            <label class="fw-semibold required">What is missing?</label>
                                            <select name="missing_info" class="form-select">
                                                <option value="">Select</option>
                                                <option value="gst_number">GST Number</option>
                                                <option value="firm_location">Firm Location</option>
                                                <option value="address">Address</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2">
                                        <div class="col-sm-6">
                                            <label class="fw-semibold required">Remarks</label>
                                            <textarea name="remarks_non" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="text-end mt-3">
                                        <button class="btn btn-danger">Submit</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Form is closed. Submitted information is shown above.
                    </div>
                @endif

            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    const uploadBox = document.getElementById('uploadBox');
                    const nonBox = document.getElementById('nonBox');
                    const nonReason = document.getElementById('nonReason');
                    const missingBox = document.getElementById('missingBox');

                    document.querySelectorAll('input[name="proposed_action"]').forEach(el => {
                        el.addEventListener('change', function() {
                            uploadBox.style.display = this.value === 'uploaded_report' ? 'block' : 'none';
                            nonBox.style.display = this.value === 'non_actionable' ? 'block' : 'none';
                        });
                    });

                    if (nonReason) {
                        nonReason.addEventListener('change', function() {
                            missingBox.style.display = this.value === 'information_incomplete' ?
                                'block' :
                                'none';
                        });
                    }
                });
            </script>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const proceedBtn = document.getElementById("proceedBtn");
                    const proceedSection = document.getElementById("proceedSection");
                    const etoForm = document.getElementById("etoForm");

                    const uploadBox = document.getElementById("uploadBox");
                    const nonBox = document.getElementById("nonBox");

                    const nonReason = document.getElementById("nonReason");
                    const missingBox = document.getElementById("missingBox");
                    const nonRemarksBox = document.getElementById("nonRemarksBox");
                    const nonSubmitBtn = document.getElementById("nonSubmitBtn");

                    proceedBtn.addEventListener("click", function() {
                        proceedSection.style.display = "none";
                        etoForm.style.display = "block";
                    });

                    document.querySelectorAll('input[name="proposed_action"]').forEach(el => {
                        el.addEventListener("change", function() {
                            uploadBox.style.display = "none";
                            nonBox.style.display = "none";

                            if (this.value === "uploaded_report") uploadBox.style.display = "block";
                            if (this.value === "non_actionable") nonBox.style.display = "block";
                        });
                    });

                    nonReason.addEventListener("change", function() {
                        missingBox.style.display = "none";
                        nonRemarksBox.style.display = "none";
                        nonSubmitBtn.style.display = "none";

                        if (this.value === "false_information" || this.value === "any_other") {
                            nonRemarksBox.style.display = "block";
                            nonSubmitBtn.style.display = "block";
                        }

                        if (this.value === "information_incomplete") {
                            missingBox.style.display = "block";
                            nonRemarksBox.style.display = "block";
                            nonSubmitBtn.style.display = "block";
                        }
                    });

                    etoForm.addEventListener("submit", function(e) {

                        let action = document.querySelector('input[name="proposed_action"]:checked');

                        if (!action) {
                            e.preventDefault();
                            return Swal.fire("Required!", "Please select Proposed Action", "warning");
                        }

                        if (action.value === "uploaded_report") {

                            let file = upload_file.files[0];

                            if (!file) {
                                e.preventDefault();
                                return Swal.fire("Required!", "Please upload a file", "warning");
                            }

                            let ext = file.name.split('.').pop().toLowerCase();
                            if (!["jpg", "jpeg", "png", "pdf"].includes(ext)) {
                                e.preventDefault();
                                return Swal.fire("Invalid!", "Only JPG, JPEG, PNG, PDF allowed!", "error");
                            }

                            if (file.size > 2 * 1024 * 1024) {
                                e.preventDefault();
                                return Swal.fire("Too Large!", "Max file size 2MB allowed", "error");
                            }

                            if (uploadRemarks.value.trim() === "") {
                                e.preventDefault();
                                return Swal.fire("Required!", "Please enter Remarks", "warning");
                            }
                        }

                        if (action.value === "non_actionable") {

                            if (nonReason.value === "") {
                                e.preventDefault();
                                return Swal.fire("Required!", "Please select Reason", "warning");
                            }

                            if (nonRemarks.value.trim() === "") {
                                e.preventDefault();
                                return Swal.fire("Required!", "Please enter Remarks", "warning");
                            }

                            if (nonReason.value === "information_incomplete" && missingInfo.value === "") {
                                e.preventDefault();
                                return Swal.fire("Required!", "Please select what is missing", "warning");
                            }
                        }
                    });
                });
            </script>
            <hr>

            <div class="text-center mt-4">
                <a href="{{ route('detc.dashboard') }}" class="btn btn-back px-4">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    {{-- ================= JS ================= --}}



    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: "Success",
                text: "{{ session('success') }}"
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "{{ session('error') }}"
            });
        </script>
    @endif
    <script>
        function validateUploadFile(input) {
            const file = input.files[0];

            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png'];
            const maxSize = 1000 * 1024;

            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Only JPG, JPEG and PNG files are allowed.',
                });

                input.value = '';
                return false;
            }


            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must not exceed 1 MB.',
                });

                input.value = '';
                return false;
            }

            return true;
        }
    </script>


    {{-- VALIDATION --}}


@endsection
