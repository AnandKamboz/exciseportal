@extends('users.layouts.dashboard')

@section('title', 'Information Details')
@section('page_title', 'Information Details')

@section('content')

    @php
        $stateName = !empty($complain->complainant_state)
            ? \App\Models\State::where('id', $complain->complainant_state)->value('name')
            : null;

        $districtName = !empty($complain->complainant_district)
            ? \App\Models\IndiaDistrict::where('id', $complain->complainant_district)->value('name')
            : null;
    @endphp


    {{-- ===================== CUSTOM STYLES ====================== --}}
    <style>
        /* Smaller equal-height cards */
        .info-card {
            min-height: 110px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 12px !important;
        }

        /* Smaller thumbnails */
        .doc-thumb {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #eee;
            cursor: zoom-in;
            transition: 0.2s;
        }

        .doc-thumb:hover {
            transform: scale(1.03);
        }
    </style>



    <div class="container-fluid py-4">
        <div class="card shadow-lg border-0 rounded-4 p-4" style="background:#FFFDF8;">


            {{-- ===================== APPLICATION DETAILS ====================== --}}
            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">📄 Application Details</h5>

            <div class="row g-3 mb-4">

                @if (!empty($complain->application_id))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Application ID:</strong><br>
                            <span class="text-secondary">{{ $complain->application_id }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->created_at))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Date of Submission:</strong><br>
                            <span class="text-secondary">{{ $complain->created_at->format('d-m-Y') }}</span>
                        </div>
                    </div>
                @endif

            </div>




            {{-- ===================== INFORMER DETAILS ====================== --}}
            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">👤 Details of Informer</h5>

            <div class="row g-3 mb-4">

                @if (!empty($complain->complainant_name))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Name:</strong><br>
                            <span class="text-secondary">{{ ucwords($complain->complainant_name) }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->complainant_phone))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Phone:</strong><br>
                            <span class="text-secondary">{{ $complain->complainant_phone }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->complainant_email))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Email:</strong><br>
                            <span class="text-secondary">{{ $complain->complainant_email }}</span>
                        </div>
                    </div>
                @endif

                @if ($stateName)
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>State:</strong><br>
                            <span class="text-secondary">{{ ucwords($stateName) }}</span>
                        </div>
                    </div>
                @endif

                @if ($districtName)
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>District:</strong><br>
                            <span class="text-secondary">{{ ucwords($districtName) }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->complainant_address))
                    <div class="col-md-12">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Address:</strong><br>
                            <span class="text-secondary">{{ ucwords($complain->complainant_address) }}</span>
                        </div>
                    </div>
                @endif

            </div>




            {{-- ===================== DETAILS OF REPORTED INFORMATION ====================== --}}
            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">📘 Details of Reported Information</h5>

            <div class="row g-3 mb-4">

                {{-- Type of Information --}}
                @if (!empty($complain->type_of_complaint))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Type of Information:</strong><br>
                            <span class="text-secondary">
                                {{ ucwords(str_replace('_', ' ', $complain->type_of_complaint)) }}
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Brief Information --}}
                @if (!empty($complain->gst_description))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Brief Information/Details:</strong><br>
                            <span class="text-secondary">{{ $complain->gst_description }}</span>
                        </div>
                    </div>
                @endif

                {{-- Location --}}
                @if (!empty($complain->location))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Location:</strong><br>
                            <span class="text-secondary">{{ $complain->location }}</span>
                        </div>
                    </div>
                @endif

                {{-- Address 1 --}}
                @if (!empty($complain->gst_address1))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Address 1:</strong><br>
                            <span class="text-secondary">{{ $complain->gst_address1 }}</span>
                        </div>
                    </div>
                @endif

                {{-- Address 2 --}}
                @if (!empty($complain->gst_address2))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Address 2:</strong><br>
                            <span class="text-secondary">{{ $complain->gst_address2 }}</span>
                        </div>
                    </div>
                @endif

                {{-- District --}}
                @if (!empty($complain->district_name))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>District:</strong><br>
                            <span class="text-secondary">{{ $complain->district_name }}</span>
                        </div>
                    </div>
                @endif

                {{-- Pincode --}}
                @if (!empty($complain->pincode))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Pincode:</strong><br>
                            <span class="text-secondary">{{ $complain->pincode }}</span>
                        </div>
                    </div>
                @endif

            </div>




            {{-- ===================== ENTITY INVOLVED DETAILS ====================== --}}
            <h5 class="fw-bold text-white p-2 rounded mb-3" style="background:#3B71CA;">
                🚚 Information About Entity Involved
            </h5>

            <div class="row g-3 mb-4">

                @if (!empty($complain->involved_type))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Entity Type:</strong><br>
                            <span class="text-secondary">{{ $complain->involved_type }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->gst_person_name))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Person Name:</strong><br>
                            <span class="text-secondary">{{ $complain->gst_person_name }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->gst_gstin))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>GSTIN:</strong><br>
                            <span class="text-secondary">{{ $complain->gst_gstin }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->gst_firm_name))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Firm Name:</strong><br>
                            <span class="text-secondary">{{ $complain->gst_firm_name }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->gst_firm_address))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Firm Address:</strong><br>
                            <span class="text-secondary">{{ $complain->gst_firm_address }}</span>
                        </div>
                    </div>
                @endif

                @if (!empty($complain->gst_vehicle_number))
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light info-card">
                            <strong>Vehicle Number:</strong><br>
                            <span class="text-secondary">{{ $complain->gst_vehicle_number }}</span>
                        </div>
                    </div>
                @endif

            </div>




            {{-- ===================== UPLOADED DOCUMENTS ====================== --}}
            @if ($complain->gst_proof || $complain->vat_proof || $complain->excise_proof)

                <h5 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4">📷 Uploaded Photographs</h5>

                <div class="row g-3">

                    @foreach (['gst_proof', 'vat_proof', 'excise_proof'] as $proofType)
                        @if ($complain->$proofType)
                            @foreach (json_decode($complain->$proofType, true) as $file)
                                @php
                                    $imgPath = asset('storage/complaints/' . $complain->application_id . '/' . $file);
                                @endphp

                                <div class="col-md-3">
                                    <a href="{{ $imgPath }}" data-lightbox="docs" data-title="Photograph">
                                        <img src="{{ $imgPath }}" class="doc-thumb">
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    @endforeach

                </div>

            @endif




            {{-- BACK BUTTON --}}
            <div class="mt-4 text-center">
                <a href="{{ route('user.dashboard') }}" class="btn btn-danger px-4">← Back to Dashboard</a>
            </div>

        </div>
    </div>

@endsection



{{-- ===================== LIGHTBOX SCRIPTS ====================== --}}
@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        lightbox.option({
            'resizeDuration': 200,
            'fadeDuration': 200,
            'wrapAround': true,
            'alwaysShowNavOnTouchDevices': true
        })
    </script>
@endsection
