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

                {{-- @if ($information->complaint_type)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Information Type</div>
                            <div class="value-text">{{ $information->complaint_type }}</div>
                        </div>
                    </div>
                @endif --}}

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

                @if ($information->gst_gstin)
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


            {{-- DETC ACTION --}}
            @if ($detcAction)
                <hr>
                <div class="section-head">DETC Action Taken</div>
                <div class="row g-3">
                    @if ($detcAction->proposed_action)
                        <div class="col-md-4">
                            <div class="info-card" style="border-left:4px solid #0a3d62;">
                                <div class="label-text">Proposed Action</div>
                                {{-- <div class="value-text">{{ $detcAction->proposed_action }}</div> --}}
                                <div class="value-text">
                                    {{ ucfirst(str_replace('_', ' ', $detcAction->proposed_action)) }}
                                </div>

                            </div>
                        </div>
                    @endif

                    @if ($detcAction->action_taken)
                        <div class="col-md-4">
                            <div class="info-card" style="border-left:4px solid #28a745;">
                                <div class="label-text">Action Taken</div>
                                <div class="value-text">{{ $detcAction->action_taken }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($detcAction->reason)
                        <div class="col-md-4">
                            <div class="info-card" style="border-left:4px solid #ffc107;">
                                <div class="label-text">Reason</div>
                                {{-- <div class="value-text">{{ $detcAction->reason }}</div> --}}
                                <div class="value-text">
                                    {{ ucwords(str_replace('_', ' ', $detcAction->reason)) }}
                                </div>

                            </div>
                        </div>
                    @endif

                    @if ($detcAction->remarks)
                        <div class="col-md-4">
                            <div class="info-card" style="border-left:4px solid #6c757d;">
                                <div class="label-text">Remarks</div>
                                <div class="value-text">{{ $detcAction->remarks }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($detcAction->file_name)
                        <div class="col-md-4">
                            <div class="info-card" style="border-left:4px solid #007bff;">
                                <div class="label-text">DETC Report</div>
                                <a href="{{ asset('storage/complaints/' . $detcAction->user_application_id . '/' . $detcAction->file_name) }}"
                                    class="btn btn-primary btn-sm mt-2" target="_blank">View Report</a>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-4">
                        <div class="info-card" style="border-left:4px solid #17a2b8;">
                            <div class="label-text">Date</div>
                            <div class="value-text">{{ $detcAction->created_at->format('d-m-Y') }}</div>
                        </div>
                    </div>

                </div>
            @endif

            {{-- BACK BUTTON --}}
            <div class="text-center mt-4">
                <a href="{{ route('hq.dashboard') }}" class="btn btn-danger px-4">← Back</a>
            </div>

        </div>
    </div>

@endsection
