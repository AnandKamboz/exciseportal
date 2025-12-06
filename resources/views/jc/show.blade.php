{{-- @extends('jc.layouts.dashboard')

@section('title', 'JC Complaint Details')
@section('page_title', 'Complaint Details')

@section('content')

    <div class="container-fluid">

        <div class="card shadow p-4">

            <h4 class="mb-3">Complaint Details</h4>

    
            <div class="row g-3">

                <div class="col-md-4">
                    <strong>Application ID:</strong><br>
                    {{ $information->application_id }}
                </div>

                <div class="col-md-4">
                    <strong>District:</strong><br>
                    {{ $information->district_name }}
                </div>

                <div class="col-md-4">
                    <strong>Date of Complaint:</strong><br>
                    {{ date('d-m-Y', strtotime($information->created_at)) }}
                </div>

            </div>

            <hr>

           
            <h5 class="mt-3">Entity Details (Submitted by Informer)</h5>

            <div class="row g-3">

                <div class="col-md-4">
                    <strong>Type of Complaint:</strong><br>
                    {{ $information->type_of_complaint ?? 'N/A' }}
                </div>

                <div class="col-md-4">
                    <strong>GST Description:</strong><br>
                    {{ $information->gst_description ?? 'N/A' }}
                </div>

                <div class="col-md-4">
                    <strong>GSTIN:</strong><br>
                    {{ $information->gst_gstin ?? 'N/A' }}
                </div>

                <div class="col-md-4">
                    <strong>Firm Name:</strong><br>
                    {{ $information->gst_firm_name ?? 'N/A' }}
                </div>

                <div class="col-md-4">
                    <strong>Firm Address:</strong><br>
                    {{ $information->gst_firm_address ?? 'N/A' }}
                </div>

                <div class="col-md-4">
                    <strong>Vehicle Number:</strong><br>
                    {{ $information->gst_vehicle_number ?? 'N/A' }}
                </div>

            </div>

          
            @if ($information->vat_firm_name || $information->vat_tin)
                <hr>
                <h5>VAT Details</h5>
                <div class="row g-3">
                    <div class="col-md-4"><strong>Firm Name:</strong><br> {{ $information->vat_firm_name }}</div>
                    <div class="col-md-4"><strong>TIN:</strong><br> {{ $information->vat_tin }}</div>
                    <div class="col-md-4"><strong>Description:</strong><br> {{ $information->vat_description }}</div>
                </div>
            @endif

         
            @if ($information->excise_name || $information->excise_desc)
                <hr>
                <h5>Excise Details</h5>
                <div class="row g-3">
                    <div class="col-md-4"><strong>Name:</strong><br> {{ $information->excise_name }}</div>
                    <div class="col-md-4"><strong>Place:</strong><br> {{ $information->excise_place }}</div>
                    <div class="col-md-4"><strong>Description:</strong><br> {{ $information->excise_desc }}</div>
                </div>
            @endif

            @if ($information->gst_proof || $information->vat_proof || $information->excise_proof)
                <hr>
                <h5>Attached Documents</h5>
                <div class="row g-3">
                    @foreach (['gst_proof' => 'GST', 'vat_proof' => 'VAT', 'excise_proof' => 'Excise'] as $key => $label)
                        @if ($information->$key)
                            @foreach (json_decode($information->$key, true) as $file)
                                <div class="col-md-4">
                                    <a href="{{ asset('storage/complaints/' . $information->application_id . '/' . $file) }}"
                                        target="_blank" class="btn btn-outline-primary w-100">
                                        View {{ $label }} Document
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @endif

            <hr>
            
            <h4 class="mt-4">Assign to DETC</h4>

            <form action="{{ route('jc.assign', ['secure_id' => $information->secure_id]) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold required">Select DETC</label>
                            <select name="detc_id" class="form-select" required>
                                <option value="">Select DETC</option>
                                @foreach ($inspectors as $i)
                                    <option value="{{ $i->district }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary px-4">Assign</button>
            </form>

        </div>
    </div>
@endsection --}}



@extends('jc.layouts.dashboard')

@section('title', 'JC Information Details')
@section('page_title', 'Information Details')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>


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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
    </style>

    <div class="container-fluid">

        <div class="card shadow p-4">

            <div class="section-head text-center">Information Details</div>

            {{-- ======================== BASIC INFO ======================== --}}
            <div class="row g-3">

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label-text">Application ID</div>
                        <div class="value-text">{{ $information->application_id }}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label-text">Date of Complaint</div>
                        <div class="value-text">{{ date('d-m-Y', strtotime($information->created_at)) }}</div>
                    </div>
                </div>

            </div>

            <hr>

            {{-- ======================== ENTITY DETAILS ======================== --}}
            <div class="section-head">Entity Details</div>

            <div class="row g-3">

                @if ($information->type_of_complaint)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Type of Information</div>
                            <div class="value-text">{{ ucwords(str_replace('_', ' ', $information->type_of_complaint)) }}
                            </div>
                        </div>
                    </div>
                @endif

                @if ($information->gst_description)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Brief Information / Details</div>
                            <div class="value-text">{{ $information->gst_description }}</div>
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

                @if ($information->gst_firm_name)
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label-text">Firm Name</div>
                            <div class="value-text">{{ $information->gst_firm_name }}</div>
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
            </div>

            {{-- ================= VAT DETAILS IF EXISTS ================= --}}
            @if ($information->vat_firm_name || $information->vat_tin)
                <hr>
                <div class="section-head">VAT Details</div>

                <div class="row g-3">

                    @if ($information->vat_firm_name)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">Firm Name</div>
                                <div class="value-text">{{ $information->vat_firm_name }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($information->vat_tin)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">TIN</div>
                                <div class="value-text">{{ $information->vat_tin }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($information->vat_description)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">Description</div>
                                <div class="value-text">{{ $information->vat_description }}</div>
                            </div>
                        </div>
                    @endif

                </div>
            @endif


            {{-- ================= EXCISE DETAILS ================= --}}
            @if ($information->excise_name || $information->excise_desc)
                <hr>
                <div class="section-head">Excise Details</div>

                <div class="row g-3">

                    @if ($information->excise_name)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">Name</div>
                                <div class="value-text">{{ $information->excise_name }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($information->excise_place)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">Place</div>
                                <div class="value-text">{{ $information->excise_place }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($information->excise_desc)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">Description</div>
                                <div class="value-text">{{ $information->excise_desc }}</div>
                            </div>
                        </div>
                    @endif

                </div>
            @endif


            {{-- ================= DOCUMENTS ================= --}}
            {{-- @if ($information->gst_proof || $information->vat_proof || $information->excise_proof)
                <hr>
                <div class="section-head">Attached Documents</div>

                <div class="row g-3">

                    @foreach (['gst_proof' => 'GST', 'vat_proof' => 'VAT', 'excise_proof' => 'Excise'] as $key => $label)
                        @if ($information->$key)
                            @foreach (json_decode($information->$key, true) as $file)
                                <div class="col-md-4">
                                    <a href="{{ asset('storage/complaints/' . $information->application_id . '/' . $file) }}"
                                        data-lightbox="docs" data-title="{{ $label }} Document"
                                        class="btn btn-outline-primary w-100">
                                        View {{ $label }} Document
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    @endforeach

                </div>
            @endif --}}

            @if ($information->gst_proof || $information->vat_proof || $information->excise_proof)
                <hr>
                <div class="section-head">Attached Documents</div>

                <div class="row g-3">

                    @foreach (['gst_proof' => 'GST', 'vat_proof' => 'VAT', 'excise_proof' => 'Excise'] as $key => $label)
                        @if ($information->$key)
                            @foreach (json_decode($information->$key, true) as $file)
                                @php
                                    $path = asset('storage/complaints/' . $information->application_id . '/' . $file);
                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                @endphp

                                <div class="col-md-4 text-center">

                                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                        {{-- IMAGE → show as thumbnail + Lightbox --}}
                                        <a href="{{ $path }}" data-lightbox="docs"
                                            data-title="{{ $label }} Document">
                                            <img src="{{ $path }}" class="img-fluid rounded shadow-sm"
                                                style="height:140px;object-fit:cover;border:1px solid #ccc;">
                                        </a>
                                        {{-- <div class="mt-2 fw-bold">{{ $label }} Document</div> --}}
                                    @else
                                        {{-- NON-IMAGE FILE (PDF etc) → Show button --}}
                                        <a href="{{ $path }}" target="_blank"
                                            class="btn btn-outline-primary w-100">
                                            View {{ $label }} Document
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    @endforeach

                </div>
            @endif
            <hr>

            {{-- ================= ASSIGN DETC ================= --}}
            {{-- <h4 class="mt-4">Assign to DETC</h4>
            <form action="{{ route('jc.assign', ['secure_id' => $information->secure_id]) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold required">Select DETC</label>
                        <select name="detc_id" class="form-select" required>
                            <option value="">Select DETC</option>
                            @foreach ($inspectors as $i)
                                <option value="{{ $i->district }}">{{ $i->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary mt-3 px-4">Assign</button>
            </form> --}}

            <h4 class="mt-4">Assign to DETC</h4>

            <div class="card shadow-lg mt-3" style="border-left:5px solid #0a3d62; border-radius:10px;">

                <div class="card-header" style="background:#0a3d62; color:white; font-weight:600;">
                    Assign Complaint to DETC
                </div>
                <div class="card-body">
                    <form action="{{ route('jc.assign', ['secure_id' => $information->secure_id]) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold required">Select DETC</label>
                                <select name="detc_id" class="form-select" required>
                                    <option value="">Select DETC</option>
                                    @foreach ($inspectors as $i)
                                        <option value="{{ $i->district }}">{{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-3 px-4">Assign</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
