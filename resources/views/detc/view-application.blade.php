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














            <div class="text-center mt-4">
                <a href="{{ route('detc.dashboard') }}" class="btn btn-back px-4">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    {{-- ================= JS ================= --}}



    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- VALIDATION --}}


@endsection
