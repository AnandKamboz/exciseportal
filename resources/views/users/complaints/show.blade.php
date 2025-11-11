@extends('users.layouts.dashboard')

@section('title', 'Information Details')
@section('page_title', 'Information Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-lg border-0 rounded-4 p-4" style="background: #FFFDF8;">
            <h4 class="fw-bold text-primary mb-4 border-bottom pb-2">📝 Information Details</h4>

            {{-- Basic Information --}}
            <div class="row g-3 mb-4">
                @if ($complain->application_id)
                    <div class="col-md-4"><strong>Application ID:</strong> {{ $complain->application_id }}</div>
                @endif
                @if ($complain->complainant_name)
                    <div class="col-md-4"><strong>Name:</strong> {{ ucfirst($complain->complainant_name) }}</div>
                @endif
                @if ($complain->complainant_phone)
                    <div class="col-md-4"><strong>Phone:</strong> {{ $complain->complainant_phone }}</div>
                @endif
                @if ($complain->complainant_email)
                    <div class="col-md-4"><strong>Email:</strong> {{ $complain->complainant_email }}</div>
                @endif
                @if ($complain->complainant_aadhaar)
                    <div class="col-md-4"><strong>Aadhaar:</strong> {{ $complain->complainant_aadhaar }}</div>
                @endif
                @if ($complain->complainant_address)
                    <div class="col-md-8"><strong>Address:</strong> {{ ucfirst($complain->complainant_address) }}</div>
                @endif
                @if ($complain->complaint_type)
                    <div class="col-md-4"><strong>Information Type:</strong> {{ strtoupper($complain->complaint_type) }}
                    </div>
                @endif
                @if ($complain->created_at)
                    <div class="col-md-4"><strong>Date:</strong> {{ $complain->created_at->format('d-m-Y') }}</div>
                @endif
            </div>

            {{-- GST / VAT / Excise Information --}}
            <hr>
            <h5 class="fw-semibold text-secondary mb-3">🏢 Related Business / Excise Information</h5>

            <div class="row g-3 mb-4">

                {{-- GST Details --}}
                @if ($complain->gst_firm_name)
                    <div class="col-md-4"><strong>Firm Name:</strong> {{ $complain->gst_firm_name }}</div>
                @endif
                @if ($complain->gst_gstin)
                    <div class="col-md-4"><strong>GSTIN:</strong> {{ $complain->gst_gstin }}</div>
                @endif
                @if ($complain->gst_firm_address)
                    <div class="col-md-8"><strong>Firm Address:</strong> {{ $complain->gst_firm_address }}</div>
                @endif
                @if ($complain->gst_locality)
                    <div class="col-md-4"><strong>Locality:</strong> {{ $complain->gst_locality }}</div>
                @endif
                {{-- @if ($complain->district)
                    <div class="col-md-4"><strong>GST District:</strong> {{ $complain->district }}</div>
                @endif --}}
                @if ($complain->gst_vehicle_number)
                    <div class="col-md-4"><strong>Vehicle Number:</strong> {{ $complain->gst_vehicle_number }}</div>
                @endif
                @if ($complain->gst_description)
                    <div class="col-md-12"><strong>Description:</strong> {{ $complain->gst_description }}</div>
                @endif

                {{-- VAT Details --}}
                @if ($complain->vat_firm_name)
                    <div class="col-md-4"><strong>Firm Name:</strong> {{ $complain->vat_firm_name }}</div>
                @endif
                @if ($complain->vat_tin)
                    <div class="col-md-4"><strong>TIN:</strong> {{ $complain->vat_tin }}</div>
                @endif
                @if ($complain->vat_firm_address)
                    <div class="col-md-8"><strong>Firm Address:</strong> {{ $complain->vat_firm_address }}</div>
                @endif
                @if ($complain->vat_locality)
                    <div class="col-md-4"><strong>Locality:</strong> {{ $complain->vat_locality }}</div>
                @endif
                {{-- @if ($complain->district)
                    <div class="col-md-4"><strong>District:</strong> {{ $complain->district }}</div>
                @endif --}}

                @php
                    $districtName = \App\Models\District::where('id', $complain->district)->value('name');
                @endphp

                @if ($districtName)
                    <div class="col-md-4"><strong>District:</strong> {{ $districtName }}</div>
                @endif


                @if ($complain->vat_vehicle_number)
                    <div class="col-md-4"><strong>Vehicle Number:</strong> {{ $complain->vat_vehicle_number }}</div>
                @endif
                @if ($complain->vat_description)
                    <div class="col-md-12"><strong>Description:</strong> {{ $complain->vat_description }}</div>
                @endif

                {{-- Excise Details --}}
                @if ($complain->excise_name)
                    <div class="col-md-4"><strong>Name:</strong> {{ $complain->excise_name }}</div>
                @endif
                @if ($complain->excise_place)
                    <div class="col-md-4"><strong>Place:</strong> {{ $complain->excise_place }}</div>
                @endif
                @if ($complain->excise_time)
                    <div class="col-md-4"><strong>Time:</strong> {{ $complain->excise_time }}</div>
                @endif
                @if ($complain->excise_vehicle_number)
                    <div class="col-md-4"><strong>Vehicle Number:</strong> {{ $complain->excise_vehicle_number }}
                    </div>
                @endif
                @if ($complain->excise_desc)
                    <div class="col-md-8"><strong>Description:</strong> {{ $complain->excise_desc }}</div>
                @endif
                @if ($complain->excise_details)
                    <div class="col-md-12"><strong>Details:</strong> {{ $complain->excise_details }}</div>
                @endif

            </div>

            {{-- Attached Proofs --}}
            @if ($complain->gst_proof || $complain->vat_proof)
                <hr>
                <h5 class="fw-semibold text-secondary mb-3">📎 Attached Documents</h5>
                <div class="row g-3 mb-3">
                    @if ($complain->gst_proof)
                        @php $gstFiles = json_decode($complain->gst_proof, true); @endphp
                        @foreach ($gstFiles as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/' . $complain->application_id . '/' . $file) }}"
                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                    View GST Proof
                                </a>
                            </div>
                        @endforeach
                    @endif
                    @if ($complain->vat_proof)
                        @php $vatFiles = json_decode($complain->vat_proof, true); @endphp
                        @foreach ($vatFiles as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/' . $complain->application_id . '/' . $file) }}"
                                    target="_blank" class="btn btn-sm btn-outline-success">
                                    View VAT Proof
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif

            <div class="mt-4 text-center">
                <a href="{{ route('user.dashboard') }}" class="btn btn-danger px-4">← Back to Dashboard</a>
            </div>

        </div>
    </div>
@endsection
