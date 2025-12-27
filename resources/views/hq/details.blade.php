@extends('hq.layouts.dashboard')

@section('title', 'Information Details')
@section('page_title', 'Information Details')

@section('content')

    <style>
        .section-head {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #0a3d62;
        }

        .info-card {
            background: #fff;
            border: 1px solid #e4e4e4;
            padding: 14px;
            border-radius: 6px;
        }

        .label-text {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .value-text {
            font-size: 15px;
            font-weight: 600;
            color: #111;
        }
    </style>

    <div class="container-fluid">
        <div class="card p-4 shadow-sm border-0" style="background:#fafafa;">

            {{-- APPLICATION ID --}}
            <div class="text-center mb-4">
                <h3 style="font-weight:700;font-size:22px;color:#0a3d62;">
                    Application ID :
                    <span style="color:#d35400;">
                        {{ $information->application_id }}
                    </span>
                </h3>
            </div>

            {{-- INFORMER DETAILS --}}
            <div class="section-head">Informer Details</div>

            <div class="row g-3">

                @if ($information->complainant_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Name</div>
                            <div class="value-text">{{ $information->complainant_name }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->complainant_phone)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Phone</div>
                            <div class="value-text">{{ $information->complainant_phone }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->complainant_email)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Email</div>
                            <div class="value-text">{{ $information->complainant_email }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->complainant_aadhar)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Aadhar Number</div>
                            <div class="value-text">{{ $information->complainant_aadhar }}</div>
                        </div>
                    </div>
                @endif

                @if ($stateName)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">State</div>
                            <div class="value-text">{{ $stateName }}</div>
                        </div>
                    </div>
                @endif

                @if ($districtName)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">District</div>
                            <div class="value-text">{{ $districtName }}</div>
                        </div>
                    </div>
                @endif

            </div>

            <hr>

            {{-- INFORMATION DETAILS --}}
            <div class="section-head">Information Details</div>

            <div class="row g-3">

                @if ($information->type_of_complaint)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Type of Information</div>
                            <div class="value-text">{{ ucfirst(str_replace('_', ' ', $information->type_of_complaint)) }}
                            </div>
                        </div>
                    </div>
                @endif

                @if ($information->gst_description)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Details / Description</div>
                            <div class="value-text">{{ $information->gst_description }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->gst_firm_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm Name</div>
                            <div class="value-text">{{ $information->gst_firm_name }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->gst_gstin && strlen($information->gst_gstin) == 15)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">GSTIN</div>
                            <div class="value-text">{{ $information->gst_gstin }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->gst_firm_address)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm Address</div>
                            <div class="value-text">{{ $information->gst_firm_address }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->location)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Location</div>
                            <div class="value-text">{{ $information->location }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->pincode)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Pincode</div>
                            <div class="value-text">{{ $information->pincode }}</div>
                        </div>
                    </div>
                @endif

                {{-- here --}}

                @if ($information->missing_gst_number)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Missing Gst Number (Provided after DETC Query)</div>
                            <div class="value-text">{{ $information->missing_gst_number }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->missing_firm_location)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Missing firm location (Provided after DETC Query)</div>
                            <div class="value-text">{{ $information->missing_firm_location }}</div>
                        </div>
                    </div>
                @endif

                @if ($information->missing_address)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Missing Firm Address (Provided after DETC Query)</div>
                            <div class="value-text">{{ $information->missing_address }}</div>
                        </div>
                    </div>
                @endif


                {{-- HERE ETO MISSING FIELD --}}

                @if ($information->eto_missing_gst_number)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">
                                GST Number (Provided after ETO Query)
                            </div>
                            <div class="value-text">
                                {{ $information->eto_missing_gst_number }}
                            </div>
                        </div>
                    </div>
                @endif

                @if ($information->eto_missing_firm_location)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">
                                Firm Location (Provided after ETO Query)
                            </div>
                            <div class="value-text">
                                {{ $information->eto_missing_firm_location }}
                            </div>
                        </div>
                    </div>
                @endif

                @if ($information->eto_missing_address)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">
                                Firm Address (Provided after ETO Query)
                            </div>
                            <div class="value-text">
                                {{ $information->eto_missing_address }}
                            </div>
                        </div>
                    </div>
                @endif




            </div>

            {{-- DOCUMENTS --}}
            @if ($information->gst_proof || $information->vat_proof || $information->excise_proof)
                <hr>
                <h4 class="fw-bold mb-3">Attached Documents</h4>

                <div class="row g-3">

                    {{-- GST --}}
                    @if ($information->gst_proof)
                        @foreach (json_decode($information->gst_proof, true) as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/' . $information->application_id . '/' . $file) }}"
                                    class="btn btn-outline-primary w-100" target="_blank">
                                    View GST Document
                                </a>
                            </div>
                        @endforeach
                    @endif

                    {{-- VAT --}}
                    @if ($information->vat_proof)
                        @foreach (json_decode($information->vat_proof, true) as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/' . $information->application_id . '/' . $file) }}"
                                    class="btn btn-outline-primary w-100" target="_blank">
                                    View VAT Document
                                </a>
                            </div>
                        @endforeach
                    @endif

                    {{-- EXCISE --}}
                    @if ($information->excise_proof)
                        @foreach (json_decode($information->excise_proof, true) as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/' . $information->application_id . '/' . $file) }}"
                                    class="btn btn-outline-primary w-100" target="_blank">
                                    View Excise Document
                                </a>
                            </div>
                        @endforeach
                    @endif

                </div>
            @endif


            {{-- DETC  --}}

            @if (!empty($actionOfDetc) && $actionOfDetc->count() > 0)
                <hr>

                <h5 class="section-head mb-3">
                    🧾 DETC Action Taken
                </h5>

                <style>
                    .info-card {
                        background: #ffffff;
                        border-radius: 12px;
                        padding: 14px 16px;
                        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
                        height: 100%;
                    }

                    .label-text {
                        font-size: 13px;
                        color: #6b7280;
                        font-weight: 600;
                        margin-bottom: 4px;
                        text-transform: uppercase;
                    }

                    .value-text {
                        font-size: 15px;
                        color: #111827;
                        font-weight: 500;
                    }

                    .action-wrapper {
                        background: #f8fafc;
                        border-radius: 14px;
                        padding: 16px;
                        margin-bottom: 20px;
                    }
                </style>

                {{-- 🔁 LOOP HERE --}}
                @foreach ($actionOfDetc as $index => $action)
                    <div class="action-wrapper">
                        <h6 class="mb-3 text-primary">
                            Action #{{ $index + 1 }}
                        </h6>

                        <div class="row g-3">

                            {{-- Proposed Action --}}
                            @if ($action->proposed_action)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #2563eb;">
                                        <div class="label-text">Proposed Action</div>
                                        <div class="value-text">
                                            {{ ucwords(str_replace('_', ' ', $action->proposed_action)) }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Action Taken --}}
                            @if ($action->action_taken)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #16a34a;">
                                        <div class="label-text">Action Taken</div>
                                        <div class="value-text">
                                            {{ $action->action_taken }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Reason --}}
                            @if ($action->reason)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #f59e0b;">
                                        <div class="label-text">Reason</div>
                                        <div class="value-text">
                                            {{ ucwords(str_replace('_', ' ', $action->reason)) }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Remarks --}}
                            @if ($action->remarks)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #64748b;">
                                        <div class="label-text">Remarks</div>
                                        <div class="value-text">
                                            {{ $action->remarks }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- DETC Report --}}
                            @if ($action->file_name)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #0ea5e9;">
                                        <div class="label-text">DETC Report</div>
                                        <a href="{{ asset('storage/complaints/' . $action->user_application_id . '/' . $action->file_name) }}"
                                            class="btn btn-primary btn-sm mt-2" target="_blank">
                                            View Report
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Date --}}
                            <div class="col-md-4">
                                <div class="info-card" style="border-left:5px solid #8b5cf6;">
                                    <div class="label-text">Action Date</div>
                                    <div class="value-text">
                                        {{ $action->created_at->format('d-m-Y') }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif



            @if (!empty($etoAction) && $etoAction->count() > 0)
                <hr>

                <h5 class="section-head mb-3">
                    🧾 ETO Action Taken
                </h5>

                <style>
                    .info-card {
                        background: #ffffff;
                        border-radius: 12px;
                        padding: 14px 16px;
                        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
                        height: 100%;
                    }

                    .label-text {
                        font-size: 13px;
                        color: #6b7280;
                        font-weight: 600;
                        margin-bottom: 4px;
                        text-transform: uppercase;
                    }

                    .value-text {
                        font-size: 15px;
                        color: #111827;
                        font-weight: 500;
                    }

                    .action-wrapper {
                        background: #f8fafc;
                        border-radius: 14px;
                        padding: 16px;
                        margin-bottom: 20px;
                    }
                </style>

                {{-- 🔁 LOOP HERE --}}
                @foreach ($etoAction as $index => $action)
                    <div class="action-wrapper">
                        <h6 class="mb-3 text-success">
                            Action #{{ $index + 1 }}
                        </h6>

                        <div class="row g-3">

                            {{-- Proposed Action --}}
                            @if ($action->proposed_action)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #22c55e;">
                                        <div class="label-text">Proposed Action</div>
                                        <div class="value-text">
                                            {{ ucwords(str_replace('_', ' ', $action->proposed_action)) }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Action Taken --}}
                            @if ($action->action_taken)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #16a34a;">
                                        <div class="label-text">Action Taken</div>
                                        <div class="value-text">
                                            {{ $action->action_taken }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Reason --}}
                            @if ($action->reason)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #f59e0b;">
                                        <div class="label-text">Reason</div>
                                        <div class="value-text">
                                            {{ ucwords(str_replace('_', ' ', $action->reason)) }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Remarks --}}
                            @if ($action->remarks)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #64748b;">
                                        <div class="label-text">Remarks</div>
                                        <div class="value-text">
                                            {{ $action->remarks }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- ETO Report --}}
                            @if ($action->file_name)
                                <div class="col-md-4">
                                    <div class="info-card" style="border-left:5px solid #0ea5e9;">
                                        <div class="label-text">ETO Report</div>
                                        <a href="{{ asset('storage/complaints/' . $action->user_application_id . '/' . $action->file_name) }}"
                                            class="btn btn-success btn-sm mt-2" target="_blank">
                                            View Report
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Date --}}
                            <div class="col-md-4">
                                <div class="info-card" style="border-left:5px solid #8b5cf6;">
                                    <div class="label-text">Action Date</div>
                                    <div class="value-text">
                                        {{ $action->created_at->format('d-m-Y') }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif






            {{-- BACK BUTTON --}}
            <div class="text-center mt-4">
                <a href="{{ route('hq.dashboard') }}" class="btn btn-danger px-4">← Back</a>
            </div>

        </div>
    </div>

@endsection
