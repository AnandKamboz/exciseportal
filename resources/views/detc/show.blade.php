@extends('detc.layouts.dashboard')

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
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label-text">District</div>
                        <div class="value-text">{{ $entityDistrict }}</div>
                    </div>
                </div>

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

                                {{-- If image → lightbox --}}
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

            {{-- ================================================= --}}
            {{-- ===================== DETC FORM =================== --}}
            {{-- ================================================= --}}

            @if (!$detcAction)

                <div class="card shadow-lg border-0 mt-5" style="border-left:5px solid #0a3d62;">

                    <div class="p-3" style="background:#0a3d62;color:white;font-weight:600;">Upload Report</div>

                    <div class="p-4">

                        {{-- Proceed Button --}}
                        <div id="proceedSection" class="text-center my-4">
                            <button class="btn btn-primary btn-lg px-4" id="proceedBtn">Proceed</button>
                        </div>

                        {{-- FORM --}}
                        <form id="detcForm" action="{{ route('detc.action.store', [$complain->secure_id]) }}"
                            method="POST" enctype="multipart/form-data" style="display:none;">

                            @csrf

                            <div class="row g-4">

                                {{-- Proposed Action --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">Proposed Action</label>
                                    <div class="radio-wrap">
                                        <label><input type="radio" name="proposed_action" value="actionable">
                                            Actionable</label>
                                        <label><input type="radio" name="proposed_action" value="non_actionable"> Non
                                            Actionable</label>
                                    </div>
                                </div>

                                {{-- Ward No (ACTIONABLE ONLY) --}}
                                <div class="col-md-6" id="actionableBox" style="display:none;">
                                    <label class="form-label fw-semibold required mt-2">
                                         {{-- Issue letter to ETO of Ward No.
                                        (Select ward dropdown) --}}
                                        Issue letter to ETO (select ward from dropdown)
                                    </label>
                                    <select class="form-select" id="wardSelect" name="ward_no">
                                        <option value="">Select Ward</option>
                                        @foreach ($wardList as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Reason (NON ACTIONABLE ONLY) --}}
                                <div class="col-md-6" id="nonActionableBox" style="display:none;">
                                    <label class="form-label fw-semibold required mt-2">Reason</label>
                                    <select class="form-select" id="reasonSelect" name="reason">
                                        <option value="">Select</option>
                                        <option value="information_incomplete">Information Incomplete</option>
                                        <option value="false_information">False Information</option>
                                        <option value="any_other">Any Other</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row">

                                {{-- Remarks (always after selecting) --}}
                                <div class="col-md-6" id="remarksBox" style="display:none;">
                                    <div class="mt-3">
                                        <label class="form-label fw-semibold required">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                                    </div>
                                </div>

                                {{-- FILE (ONLY ACTIONABLE) --}}
                                <div class="col-md-6" id="fileBox" style="display:none;">
                                    <div class="mt-3">
                                        <label class="form-label fw-semibold required">Upload copy of order issued to
                                            concerned ETO</label>
                                        <input type="file" class="form-control" name="upload_file" id="upload_file"
                                            accept=".jpg,.jpeg,.png">
                                    </div>
                                </div>

                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary mt-4 px-5 py-2">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>
            @else
                {{-- ================= SHOW UPLOADED REPORT ================= --}}
                <div class="card shadow-lg border-0 mt-4">
                    <div class="card-header" style="background:#0a3d62;color:white;">
                        <h5 class="fw-bold text-white">Uploaded Report Details</h5>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="p-3 shadow-sm rounded" style="border-left:4px solid #0a3d62;">
                                    <small class="text-secondary">Proposed Action</small>
                                    <div class="fw-bold">
                                        {{ ucwords(str_replace('_', ' ', $detcAction->proposed_action)) }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 shadow-sm rounded" style="border-left:4px solid #6c757d;">
                                    <small class="text-secondary">Remarks</small>
                                    <div class="fw-bold">{{ $detcAction->remarks }}</div>
                                </div>
                            </div>

                            {{-- SHOW UPLOADED FILE --}}
                            @if ($detcAction->file_name)
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #007bff;">
                                        <small class="text-secondary">Uploaded Document</small>
                                        <a href="{{ asset('storage/complaints/' . $complain->application_id . '/' . $detcAction->file_name) }}"
                                            target="_blank" class="btn btn-primary btn-sm mt-2">View Document</a>
                                    </div>
                                </div>
                            @endif


                            @if (!empty($detcAction->ward_no))
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #6c757d;">
                                        <small class="text-secondary">Ward No</small>
                                        <div class="fw-bold">Ward no {{ $detcAction->ward_no }}</div>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($detcAction->reason))
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #6c757d;">
                                        <small class="text-secondary">Reason</small>
                                          <div class="fw-bold">{{ ucwords(str_replace('_', ' ', $detcAction->reason)) }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <div class="text-center mt-4">
                <a href="{{ route('detc.dashboard') }}" class="btn btn-back px-4">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    {{-- ================= JS ================= --}}

    <script>
        document.getElementById('proceedBtn').onclick = () => {
            document.getElementById('proceedSection').style.display = 'none';
            document.getElementById('detcForm').style.display = 'block';
        };
    </script>

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

    {{-- VALIDATION --}}
    <script>
        document.getElementById("detcForm").addEventListener("submit", function(e) {

            let proposed = document.querySelector('input[name="proposed_action"]:checked');
            let wardNo = document.getElementById('wardSelect').value;
            let reason = document.getElementById('reasonSelect').value;
            let remarks = document.getElementById('remarks').value.trim();
            let file = document.getElementById('upload_file').files[0];

            if (!proposed) {
                e.preventDefault();
                Swal.fire("Required!", "Please select Proposed Action", "warning");
                return;
            }

            if (proposed.value === "actionable" && wardNo === "") {
                e.preventDefault();
                Swal.fire("Required!", "Please select Ward No", "warning");
                return;
            }

            if (proposed.value === "non_actionable" && reason === "") {
                e.preventDefault();
                Swal.fire("Required!", "Please select Reason", "warning");
                return;
            }

            if (remarks === "") {
                e.preventDefault();
                Swal.fire("Required!", "Please enter Remarks", "warning");
                return;
            }

            if (proposed.value === "actionable") {

                if (!file) {
                    e.preventDefault();
                    Swal.fire("Required!", "File is required for actionable!", "warning");
                    return;
                }

                let ext = file.name.split('.').pop().toLowerCase();
                if (!["jpg", "jpeg", "png"].includes(ext)) {
                    e.preventDefault();
                    Swal.fire("Invalid File!", "Only JPG, JPEG, PNG allowed!", "error");
                    return;
                }

                if (file.size > 1024 * 1024) {
                    e.preventDefault();
                    Swal.fire("Too Large!", "File must be less than 1MB!", "error");
                    return;
                }
            }
        });
    </script>

    {{-- SHOW/HIDE FIELDS --}}
    <script>
        document.querySelectorAll('input[name="proposed_action"]').forEach((el) => {
            el.addEventListener('change', function() {

                let act = document.getElementById('actionableBox');
                let non = document.getElementById('nonActionableBox');
                let fileBox = document.getElementById('fileBox');
                let remarksBox = document.getElementById('remarksBox');

                if (this.value === "actionable") {
                    act.style.display = "block";
                    non.style.display = "none";
                    fileBox.style.display = "block";
                    remarksBox.style.display = "block";
                } else if (this.value === "non_actionable") {
                    act.style.display = "none";
                    non.style.display = "block";
                    fileBox.style.display = "none";
                    remarksBox.style.display = "block";
                }
            });
        });
    </script>

@endsection
