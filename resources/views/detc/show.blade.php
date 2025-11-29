@extends('users.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Complaint Details')

@section('content')
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
            color: #374151;
            font-weight: 600;
        }

        .value-text {
            font-size: 15px;
            font-weight: 500;
            color: #111;
        }

        .btn-primary {
            background: #0a3d62;
            border: none;
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
            margin-top: 6px;
            padding: 8px 10px;
            background: #f3f7ff;
            border-radius: 6px;
            border: 1px solid #d7dff8;
        }
    </style>

    <div class="container-fluid">

        <div class="card p-4 mb-4 shadow-sm border-0" style="background:#fafafa;">

            <div class="section-head text-center">Information Details</div>

            {{-- INFORMER DETAILS --}}
            <div class="section-head mt-3">Informer Details</div>

            <div class="row g-3">
                @php $info=['application_id'=>'Application ID']; @endphp

                @foreach ($info as $col => $label)
                    @if ($complain->$col)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">{{ $label }}</div>
                                <div class="value-text">{{ $complain->$col }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label-text">Date of Information</div>
                        <div class="value-text">{{ $complain->created_at->format('d-m-Y') }}</div>
                    </div>
                </div>
            </div>

            <hr>

            {{-- ENTITY DETAILS --}}
            <div class="section-head">Entity Details</div>

            <div class="row g-3">

                @php
                    $fields = [
                        'type_of_complaint' => 'Type of Information',
                        'gst_description' => 'GST Description',
                        'complainant_district' => 'District',
                        'pincode' => 'Pincode',
                        'involved_type' => 'Involved Type',
                        'gst_firm_name' => 'Firm Name',
                        'gst_gstin' => 'Gstin',
                        'gst_firm_address' => 'Firm Address',
                        'location' => 'Location',
                        'gst_address2' => 'Address Line 2',
                        'gst_locality' => 'Locality',
                        'gst_city' => 'City',
                        'gst_vehicle_number' => 'Vehicle Number',
                        'vat_firm_name' => 'VAT Firm Name',
                        'vat_tin' => 'VAT TIN',
                        'vat_firm_address' => 'VAT Firm Address',
                        'vat_person_name' => 'VAT Person Name',
                        'vat_vehicle_number' => 'VAT Vehicle Number',
                        'vat_description' => 'VAT Description',
                        'excise_name' => 'Excise Name',
                        'excise_place' => 'Excise Place',
                        'excise_time' => 'Excise Time',
                        'excise_vehicle_number' => 'Excise Vehicle Number',
                        'excise_desc' => 'Excise Description',
                        'excise_details' => 'Excise Details',
                    ];
                @endphp

                @foreach ($fields as $col => $label)
                    @if ($col == 'complainant_district')
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">{{ $label }}</div>
                                <div class="value-text">{{ $entityDistrict ?? 'N/A' }}</div>
                            </div>
                        </div>
                        @continue
                    @endif

                    @if ($complain->$col)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">{{ $label }}</div>
                                <div class="value-text">
                                    {{ ucwords(str_replace('_', ' ', $complain->$col)) }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- DOCUMENTS --}}
            @if ($complain->gst_proof || $complain->vat_proof || $complain->excise_proof)
                <hr>
                <div class="section-head">Attached Documents</div>

                <div class="row g-3">
                    @foreach (['gst_proof' => 'GST', 'vat_proof' => 'VAT', 'excise_proof' => 'Excise'] as $key => $label)
                        @if ($complain->$key)
                            @foreach (json_decode($complain->$key, true) as $file)
                                <div class="col-md-4">
                                    <a href="{{ asset('storage/complaints/' . $complain->application_id . '/' . $file) }}"
                                        target="_blank" class="btn btn-outline-secondary w-100">View {{ $label }}</a>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @endif

            <hr>

            {{-- ************* UPDATED FORM AREA START ************** --}}
            @if (!$detcAction)

                <div class="card shadow-lg border-0 mt-5"
                    style="background:#ffffff;border-radius:12px;border-left:5px solid #0a3d62;">

                    <div class="p-3" style="background:#0a3d62;border-radius:12px 12px 0 0;color:white;font-weight:600;">
                        DETC Action Form
                    </div>

                    <div class="p-4">

                        {{-- PROCEED BUTTON --}}
                        <div id="proceedSection" class="text-center my-4">
                            <button class="btn btn-primary btn-lg px-4" id="proceedBtn"
                                style="font-size:18px;">Proceed</button>
                        </div>

                        {{-- FORM --}}
                        <form id="detcForm" action="{{ route('detc.action.store', [$complain->secure_id]) }}"
                            method="POST" style="display:none;" enctype="multipart/form-data">

                            @csrf

                            <div class="row g-4">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required">Proposed Action</label>

                                    <div class="radio-wrap"
                                        style="background:#eff4ff;border:1px solid #c7d6ff;border-radius:8px;padding:12px;">
                                        <label><input type="radio" name="proposed_action" value="actionable">
                                            Actionable</label>
                                        <label class="ms-4"><input type="radio" name="proposed_action"
                                                value="non_actionable"> Non Actionable</label>
                                    </div>
                                </div>



                                <div class="col-md-6" id="actionableBox" style="display:none;">
                                    <label class="form-label fw-semibold required mt-2">Select Action</label>
                                    <select class="form-select" id="actionSelect" name="action_taken">
                                        <option value="">Select</option>
                                        <option value="action_taken">Action Taken</option>
                                        <option value="tax_evasion">Tax Evasion</option>
                                        <option value="any_other">Any Other</option>
                                    </select>
                                </div>

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
                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <label class="form-label fw-semibold required">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Add remarks..."></textarea>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6">
                                    <div class="mt-3">
                                        <label class="form-label fw-semibold required">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Add remarks..." required></textarea>
                                    </div>
                                </div> --}}

                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <label class="form-label fw-semibold">Upload File</label>
                                        <input type="file" class="form-control" name="upload_file" id="upload_file">
                                    </div>
                                </div>

                            </div>



                            <div class="text-end">
                                <button type="submit" class="btn btn-primary mt-4 px-5 py-2"
                                    style="font-size:17px;border-radius:6px;">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- *************** FORM END **************** --}}
            @else
                {{-- SAME CODE --}}
                <div class="card shadow-lg border-0 mt-4" style="border-radius:12px;">
                    <div class="card-header"
                        style="background:linear-gradient(45deg,#0a3d62,#1e5f9d);color:white;border-radius:12px 12px 0 0;">
                        <h5 class="mb-0 fw-bold text-white">DETC Action Taken</h5>
                    </div>

                    <div class="card-body" style="background:#ffffff;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 shadow-sm rounded"
                                    style="background:#f8f9fa;border-left:4px solid #0a3d62;">
                                    <small class="text-secondary d-block">Proposed Action</small>
                                    <span class="fw-bold text-dark fs-6">
                                        {{ ucwords(str_replace('_', ' ', $detcAction->proposed_action)) }}
                                    </span>
                                </div>
                            </div>
                            @if ($detcAction->action_taken)
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded"
                                        style="background:#f8f9fa;border-left:4px solid #28a745;">
                                        <small class="text-secondary d-block">Action Taken</small>
                                        <span class="fw-bold text-dark fs-6">
                                            {{ ucwords(str_replace('_', ' ', $detcAction->action_taken)) }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($detcAction->reason)
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded"
                                        style="background:#f8f9fa;border-left:4px solid #ffc107;">
                                        <small class="text-secondary d-block">Reason</small>
                                        <span class="fw-bold text-dark fs-6">
                                            {{ ucwords(str_replace('_', ' ', $detcAction->reason)) }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <div class="p-3 shadow-sm rounded"
                                    style="background:#f8f9fa;border-left:4px solid #6c757d;">
                                    <small class="text-secondary d-block">Remarks</small>
                                    <span class="fw-bold text-dark fs-6">
                                        {{ $detcAction->remarks }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 shadow-sm rounded"
                                    style="background:#f8f9fa;border-left:4px solid #17a2b8;">
                                    <small class="text-secondary d-block">Date</small>
                                    <span class="fw-bold text-dark fs-6">
                                        <p><strong>Date:</strong> {{ $detcAction->created_at->format('d-m-Y') }}</p>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('detc.dashboard') }}" class="btn btn-back px-4">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        document.getElementById('proceedBtn').onclick = () => {
            document.getElementById('proceedSection').style.display = 'none';
            document.getElementById('detcForm').style.display = 'block';
        };

        document.querySelectorAll('input[name="proposed_action"]').forEach((el) => {
            el.addEventListener('change', function() {
                let act = document.getElementById('actionableBox');
                let non = document.getElementById('nonActionableBox');

                let actionSel = document.getElementById('actionSelect');
                let reasonSel = document.getElementById('reasonSelect');

                if (this.value === "actionable") {
                    act.style.display = "block";
                    non.style.display = "none";
                    reasonSel.value = "";
                } else if (this.value === "non_actionable") {
                    act.style.display = "none";
                    non.style.display = "block";
                    actionSel.value = "";
                } else {
                    act.style.display = "none";
                    non.style.display = "none";
                    actionSel.value = "";
                    reasonSel.value = "";
                }
            });
        });
    </script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

    {{-- <script>
        document.getElementById("detcForm").addEventListener("submit", function(e) {

            let proposed = document.querySelector('input[name="proposed_action"]:checked');
            let actionable = document.getElementById('actionSelect').value;
            let nonActionable = document.getElementById('reasonSelect').value;
            let fileInput = document.getElementById('upload_file');
            let file = fileInput.files[0];

            // 1. Proposed Action Required
            if (!proposed) {
                e.preventDefault();
                Swal.fire("Required!", "Please select Proposed Action", "warning");
                return false;
            }

            // 2. If actionable selected then Action Taken required
            if (proposed.value === "actionable" && actionable === "") {
                e.preventDefault();
                Swal.fire("Required!", "Please select Action Taken", "warning");
                return false;
            }

            // 3. If non-actionable selected then Reason required
            if (proposed.value === "non_actionable" && nonActionable === "") {
                e.preventDefault();
                Swal.fire("Required!", "Please select Reason", "warning");
                return false;
            }

            // 4. File Validation (optional but if exists then check)
            if (file) {

                let allowed = ["pdf", "jpg", "jpeg"];
                let ext = file.name.split('.').pop().toLowerCase();

                if (!allowed.includes(ext)) {
                    e.preventDefault();
                    Swal.fire("Invalid File!", "Only pdf, jpg, jpeg allowed!", "error");
                    return false;
                }

                if (file.size > 1024 * 1024) {
                    e.preventDefault();
                    Swal.fire("Too Large!", "File must be less than 1MB!", "error");
                    return false;
                }
            }

        });
    </script> --}}

    <script>
        document.getElementById("detcForm").addEventListener("submit", function(e) {

            let proposed = document.querySelector('input[name="proposed_action"]:checked');
            let actionable = document.getElementById('actionSelect').value;
            let nonActionable = document.getElementById('reasonSelect').value;
            let remarks = document.getElementById('remarks').value.trim();
            let fileInput = document.getElementById('upload_file');
            let file = fileInput.files[0];

            // 1. Proposed Action Required
            if (!proposed) {
                e.preventDefault();
                Swal.fire("Required!", "Please select Proposed Action", "warning");
                return false;
            }

            // 2. If actionable selected then Action Taken required
            if (proposed.value === "actionable" && actionable === "") {
                e.preventDefault();
                Swal.fire("Required!", "Please select Action Taken", "warning");
                return false;
            }

            // 3. If non-actionable selected then Reason required
            if (proposed.value === "non_actionable" && nonActionable === "") {
                e.preventDefault();
                Swal.fire("Required!", "Please select Reason", "warning");
                return false;
            }

            // 4. Remarks Required
            if (remarks === "") {
                e.preventDefault();
                Swal.fire("Required!", "Please enter Remarks", "warning");
                return false;
            }

            // 5. File Validation (optional)
            if (file) {

                let allowed = ["pdf", "jpg", "jpeg"];
                let ext = file.name.split('.').pop().toLowerCase();

                if (!allowed.includes(ext)) {
                    e.preventDefault();
                    Swal.fire("Invalid File!", "Only pdf, jpg, jpeg allowed!", "error");
                    return false;
                }

                if (file.size > 1024 * 1024) {
                    e.preventDefault();
                    Swal.fire("Too Large!", "File must be less than 1MB!", "error");
                    return false;
                }
            }
        });
    </script>



@endsection
