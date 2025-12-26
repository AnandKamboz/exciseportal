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
                        {{ $application->application_id }}
                    </span>
                </h3>
            </div>

            {{-- INFORMER DETAILS --}}
            <div class="section-head">Informer Details</div>

            <div class="row g-3">

                @if ($application->complainant_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Name</div>
                            <div class="value-text">{{ $application->complainant_name }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->complainant_phone)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Phone</div>
                            <div class="value-text">{{ $application->complainant_phone }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->complainant_email)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Email</div>
                            <div class="value-text">{{ $application->complainant_email }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->complainant_aadhar)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Aadhar Number</div>
                            <div class="value-text">{{ $application->complainant_aadhar }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->district_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">District</div>
                            <div class="value-text">{{ $application->district_name }}</div>
                        </div>
                    </div>
                @endif

            </div>

            <hr>

            {{-- INFORMATION DETAILS --}}
            <div class="section-head">Information Details</div>

            <div class="row g-3">

                @if ($application->type_of_complaint)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Type of Information</div>
                            <div class="value-text">
                                {{ ucwords(str_replace('_', ' ', $application->type_of_complaint)) }}
                            </div>
                        </div>
                    </div>
                @endif

                @if ($application->gst_description)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Details / Description</div>
                            <div class="value-text">{{ $application->gst_description }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->gst_firm_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm Name</div>
                            <div class="value-text">{{ $application->gst_firm_name }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->gst_gstin && strlen($application->gst_gstin) == 15)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">GSTIN</div>
                            <div class="value-text">{{ $application->gst_gstin }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->gst_firm_address)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm Address</div>
                            <div class="value-text">{{ $application->gst_firm_address }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->location)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Location</div>
                            <div class="value-text">{{ $application->location }}</div>
                        </div>
                    </div>
                @endif

                @if ($application->pincode)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Pincode</div>
                            <div class="value-text">{{ $application->pincode }}</div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- DOCUMENTS --}}
            @if ($application->gst_proof || $application->vat_proof || $application->excise_proof)
                <hr>
                <div class="section-head">Attached Documents</div>

                <div class="row g-3">

                    @if ($application->gst_proof)
                        @foreach (json_decode($application->gst_proof, true) as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/' . $application->application_id . '/' . $file) }}"
                                    class="btn btn-outline-primary w-100" target="_blank">
                                    View GST Document
                                </a>
                            </div>
                        @endforeach
                    @endif

                    @if ($application->vat_proof)
                        @foreach (json_decode($application->vat_proof, true) as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/' . $application->application_id . '/' . $file) }}"
                                    class="btn btn-outline-primary w-100" target="_blank">
                                    View VAT Document
                                </a>
                            </div>
                        @endforeach
                    @endif

                    @if ($application->excise_proof)
                        @foreach (json_decode($application->excise_proof, true) as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/' . $application->application_id . '/' . $file) }}"
                                    class="btn btn-outline-primary w-100" target="_blank">
                                    View Excise Document
                                </a>
                            </div>
                        @endforeach
                    @endif

                </div>
            @endif

            {{-- BACK BUTTON --}}
            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-danger px-4">
                    ← Back
                </a>
            </div>

        </div>
    </div>

@endsection
