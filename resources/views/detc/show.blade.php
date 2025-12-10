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
                            <div class="value-text">{{ \Carbon\Carbon::parse($complain->missing_info_submitted_at)->format('Y-m-d') }}</div>
                        </div>
                    </div>
                @endif
                {{-- Here --}}
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

            @if ($detcActions->count() > 0)
                @foreach ($detcActions as $action)
                    <div class="card shadow-lg border-0 mt-5">
                        <div class="p-3" style="background:#0a3d62;color:white;font-weight:600;">
                            Action Taken by DETC ({{ $action->created_at->format('d-m-Y') }})
                        </div>

                        <div class="p-4">
                            <div class="row g-4">

                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #0a3d62;">
                                        <small class="text-secondary">Proposed Action</small>
                                        <div class="fw-bold">
                                            {{ ucwords(str_replace('_', ' ', $action->proposed_action)) }}
                                        </div>
                                    </div>
                                </div>

                                @if ($action->ward_no)
                                    <div class="col-md-6">
                                        <div class="p-3 shadow-sm rounded" style="border-left:4px solid #007bff;">
                                            <small class="text-secondary">Ward No</small>
                                            <div class="fw-bold">Ward No {{ $action->ward_no }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($action->reason)
                                    <div class="col-md-6">
                                        <div class="p-3 shadow-sm rounded" style="border-left:4px solid #6c757d;">
                                            <small class="text-secondary">Reason</small>
                                            <div class="fw-bold">{{ ucwords(str_replace('_', ' ', $action->reason)) }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($action->missing_info)
                                    <div class="col-md-6">
                                        <div class="p-3 shadow-sm rounded" style="border-left:4px solid #20c997;">
                                            <small class="text-secondary">Missing Info</small>
                                            <div class="fw-bold">
                                                {{ ucwords(str_replace('_', ' ', $action->missing_info)) }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($action->remarks)
                                    <div class="col-md-6">
                                        <div class="p-3 shadow-sm rounded" style="border-left:4px solid #6f42c1;">
                                            <small class="text-secondary">Remarks</small>
                                            <div class="fw-bold">{{ $action->remarks }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($action->file_name)
                                    <div class="col-md-6">
                                        <div class="p-3 shadow-sm rounded" style="border-left:4px solid #ffc107;">
                                            <small>Uploaded File</small><br>
                                            <a href="{{ asset('storage/complaints/' . $action->user_application_id . '/' . $action->file_name) }}"
                                                target="_blank" class="btn btn-primary btn-sm mt-2">
                                                View Document
                                            </a>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- ================================================= --}}
            {{-- ===================== DETC FORM =================== --}}
            {{-- ================================================= --}}

            @if ($showForm)
                {{-- @if ($detcActions->count() == 0 || $detcActions[0]->reason == 'information_incomplete') --}}
                {{-- @if ($detcActions->count() == 0 || ($detcActions->count() > 0 && $detcActions->last()->reason == 'information_incomplete') || (is_null($complain->missing_gst_number) && is_null($complain->missing_firm_location) && is_null($complain->missing_address))) --}}
                <div class="card shadow-lg border-0 mt-5" style="border-left:5px solid #0a3d62;">
                    <div class="p-3" style="background:#0a3d62;color:white;font-weight:600;">Upload Report</div>

                    <div class="p-4">

                        <div id="proceedSection" class="text-center my-4">
                            <button type="button" class="btn btn-primary btn-lg px-4" id="proceedBtn">Proceed</button>
                        </div>

                        <form id="detcForm" action="{{ route('detc.action.store', [$complain->secure_id]) }}"
                            method="POST" enctype="multipart/form-data" style="display:none;">
                            @csrf

                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold required">Proposed Action</label>
                                    <div class="radio-wrap">
                                        <label><input type="radio" name="proposed_action" value="forward_to_eto">
                                            Forward to ETO</label>
                                        <label class="ms-3"><input type="radio" name="proposed_action"
                                                value="uploaded_report"> Upload Report</label>



                                        <label class="ms-3"
                                            @if ($detcActions->last() && $detcActions->last()->reason == 'information_incomplete') style="display:none;" @endif>
                                            <input type="radio" name="proposed_action" value="non_actionable">
                                            Non Actionable
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="forwardBox" style="display:none;">
                                <div class="row g-3 mt-3">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold required">Select Ward No</label>
                                        <select class="form-select" id="wardSelect" name="ward_no">
                                            <option value="">Select Ward</option>
                                            @foreach ($wardList as $key => $val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold required">Remarks</label>
                                        <textarea class="form-control" id="forwardRemarks" name="remarks" rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit" name="btn" value="submit"
                                        class="btn btn-primary px-4">Forward to ETO</button>
                                </div>
                            </div>

                            <div id="uploadBox" style="display:none;">
                                <div class="row g-3 mt-3">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold required">Upload Document / ATR</label>
                                        <input type="file" name="upload_file" id="upload_file" class="form-control"
                                            accept=".jpg,.jpeg,.png">
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold required">Remarks</label>
                                        <textarea class="form-control" id="uploadRemarks" name="remarks" rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit" name="btn" value="send_to_hq"
                                        class="btn btn-success px-4">Send to HQ</button>
                                </div>
                            </div>

                            <div id="nonBox" style="display:none;">

                                <div class="row g-3 mt-3">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold required">Select Reason</label>
                                        <select class="form-select" id="nonReasonSelect" name="reason">
                                            <option value="">Select</option>
                                            <option value="false_information">False Information</option>
                                            <option value="information_incomplete">Information Incomplete</option>


                                            <option value="any_other">Any Other</option>
                                        </select>
                                    </div>

                                    <div id="missingBox" class="col-sm-6" style="display:none;">
                                        <label class="form-label fw-semibold required">What is missing?</label>
                                        <select name="missing_info" id="missingInfo" class="form-select">
                                            <option value="">Select field</option>
                                            <option value="gst_number">GST Number</option>
                                            <option value="firm_location">Firm Name</option>
                                            <option value="address">Firm Address</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2" id="nonRemarksBox" style="display:none;">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold required">Remarks</label>
                                        <textarea class="form-control" name="remarks" rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button id="nonSubmitBtn" type="submit" name="btn" value="submit"
                                        class="btn btn-primary px-4" style="display:none;">Submit</button>

                                    <button id="nonRejectBtn" type="submit" name="btn" value="reject"
                                        class="btn btn-danger px-4 d-none" style="display:none;">Reject</button>

                                    <button id="sendBackBtn" type="submit" name="btn"
                                        value="send_back_to_applicant" class="btn btn-warning px-4"
                                        style="display:none;">Send Back To Applicant</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif


            {{-- ======================= SHOW ALL SAVED REPORTS ======================= --}}






            {{-- @if ($detcAction)
                <div class="card shadow-lg border-0 mt-5">
                    <div class="p-3" style="background:#0a3d62;color:white;font-weight:600;">
                        Saved Report Details
                    </div>

                    <div class="p-4">

                        <div class="row g-4">


                            <div class="col-md-6">
                                <div class="p-3 shadow-sm rounded" style="border-left:4px solid #0a3d62;">
                                    <small class="text-secondary">Proposed Action</small>
                                    <div class="fw-bold text-dark">
                                        {{ ucwords(str_replace('_', ' ', $detcAction->proposed_action)) }}
                                    </div>
                                </div>
                            </div>


                            @if ($detcAction->ward_no)
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #007bff;">
                                        <small class="text-secondary">Ward No</small>
                                        <div class="fw-bold text-dark">
                                            Ward No {{ $detcAction->ward_no }}
                                        </div>
                                    </div>
                                </div>
                            @endif



                            @if ($detcAction->reason)
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #6c757d;">
                                        <small class="text-secondary">Reason</small>
                                        <div class="fw-bold text-dark">
                                            {{ ucwords(str_replace('_', ' ', $detcAction->reason)) }}
                                        </div>
                                    </div>
                                </div>
                            @endif



                            @if ($detcAction->missing_info)
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #20c997;">
                                        <small class="text-secondary">Missing Info</small>
                                        <div class="fw-bold text-dark">
                                            {{ ucwords(str_replace('_', ' ', $detcAction->missing_info)) }}
                                        </div>
                                    </div>
                                </div>
                            @endif



                            @if ($detcAction->remarks)
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #6f42c1;">
                                        <small class="text-secondary">Remarks</small>
                                        <div class="fw-bold text-dark">
                                            {{ $detcAction->remarks }}
                                        </div>
                                    </div>
                                </div>
                            @endif



                            @if ($detcAction->file_name)
                                <div class="col-md-6">
                                    <div class="p-3 shadow-sm rounded" style="border-left:4px solid #ffc107;">
                                        <small class="text-secondary">Uploaded Document</small><br>
                                        <a href="{{ asset('storage/complaints/' . $complain->application_id . '/' . $detcAction->file_name) }}"
                                            target="_blank" class="btn btn-primary btn-sm mt-2">
                                            View Document
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endif --}}
















            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("proceedBtn").addEventListener("click", function() {
                        document.getElementById("proceedSection").style.display = "none";
                        document.getElementById("detcForm").style.display = "block";
                    });

                });

                document.querySelectorAll('input[name="proposed_action"]').forEach(el => {
                    el.addEventListener('change', function() {

                        forwardBox.style.display = "none";
                        uploadBox.style.display = "none";
                        nonBox.style.display = "none";

                        if (this.value === "forward_to_eto") forwardBox.style.display = "block";
                        if (this.value === "uploaded_report") uploadBox.style.display = "block";
                        if (this.value === "non_actionable") nonBox.style.display = "block";
                    });
                });


                document.getElementById("nonReasonSelect").addEventListener("change", function() {

                    missingBox.style.display = "none";
                    nonRemarksBox.style.display = "none";
                    nonSubmitBtn.style.display = "none";
                    nonRejectBtn.style.display = "none";
                    sendBackBtn.style.display = "none";

                    if (this.value === "false_information") {
                        nonRemarksBox.style.display = "block";
                        nonSubmitBtn.style.display = "block";
                    }

                    if (this.value === "any_other") {
                        nonRemarksBox.style.display = "block";
                        nonSubmitBtn.style.display = "block";
                        nonRejectBtn.style.display = "block";
                    }

                    if (this.value === "information_incomplete") {
                        missingBox.style.display = "block";
                        nonRemarksBox.style.display = "block";
                        sendBackBtn.style.display = "block";
                    }

                });



                document.getElementById("detcForm").addEventListener("submit", function(e) {

                    let action = document.querySelector('input[name="proposed_action"]:checked');

                    if (!action) {
                        e.preventDefault();
                        return Swal.fire("Required!", "Please select Proposed Action", "warning");
                    }

                    action = action.value;

                    if (action === "forward_to_eto") {

                        if (wardSelect.value === "") {
                            e.preventDefault();
                            return Swal.fire("Required!", "Please select Ward No", "warning");
                        }

                        if (forwardRemarks.value.trim() === "") {
                            e.preventDefault();
                            return Swal.fire("Required!", "Please enter Remarks", "warning");
                        }
                    }

                    if (action === "uploaded_report") {

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

                    if (action === "non_actionable") {

                        let reason = nonReasonSelect.value;

                        if (reason === "") {
                            e.preventDefault();
                            return Swal.fire("Required!", "Please select Reason", "warning");
                        }

                        let remarks = document.querySelector("#nonRemarksBox textarea")?.value.trim();


                        if (reason === "false_information" && !remarks) {
                            e.preventDefault();
                            return Swal.fire("Required!", "Please enter Remarks", "warning");
                        }

                        if (reason === "any_other" && !remarks) {
                            e.preventDefault();
                            return Swal.fire("Required!", "Please enter Remarks", "warning");
                        }

                        if (reason === "information_incomplete") {

                            if (missingInfo.value === "") {
                                e.preventDefault();
                                return Swal.fire("Required!", "Please select what is missing", "warning");
                            }

                            if (!remarks) {
                                e.preventDefault();
                                return Swal.fire("Required!", "Please enter Remarks", "warning");
                            }
                        }
                    }

                });
            </script>


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

    {{-- VALIDATION --}}


@endsection
