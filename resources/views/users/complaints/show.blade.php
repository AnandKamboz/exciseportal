@extends('users.layouts.dashboard')

@section('title', 'Information Details')
@section('page_title', 'Information Details')

@section('content')

    @php
        // State Name
        $stateName = null;
        if (!empty($complain->complainant_state)) {
            $stateName = \App\Models\State::where('id', $complain->complainant_state)->value('name');
        }

        // District Name
        $districtName = null;
        if (!empty($complain->complainant_district)) {
            $districtName = \App\Models\IndiaDistrict::where('id', $complain->complainant_district)->value('name');
        }
    @endphp


    <div class="container-fluid py-4">
        <div class="card shadow-lg border-0 rounded-4 p-4" style="background:#FFFDF8;">

            <h4 class="fw-bold text-primary mb-4 border-bottom pb-2">📝 Information Details</h4>

            {{-- ===================== SECTION 1 ====================== --}}
            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">
                👤 Informer Details
            </h5>

            @php
                $informerFields = [
                    'application_id' => 'Application ID',
                    'complainant_name' => 'Name',
                    'complainant_phone' => 'Phone',
                    'complainant_email' => 'Email',
                    'complainant_aadhar' => 'Aadhar Number',
                    'complainant_state' => 'State',
                    'complainant_district' => 'District',
                    'complainant_address' => 'Address',
                ];
            @endphp

            <div class="row g-3 mb-4">
                @foreach ($informerFields as $col => $label)
                    {{-- Special State --}}
                    @if ($col == 'complainant_state' && $stateName)
                        <div class="col-md-4">
                            <div class="p-3 shadow-sm rounded bg-light">
                                <strong>{{ $label }}:</strong><br>
                                <span class="text-secondary">{{ ucwords($stateName) }}</span>
                            </div>
                        </div>
                        @continue
                    @endif

                    {{-- Special District --}}
                    @if ($col == 'complainant_district' && $districtName)
                        <div class="col-md-4">
                            <div class="p-3 shadow-sm rounded bg-light">
                                <strong>{{ $label }}:</strong><br>
                                <span class="text-secondary">{{ ucwords($districtName) }}</span>
                            </div>
                        </div>
                        @continue
                    @endif

                    @if (!empty($complain->$col))
                        <div class="col-md-4">
                            <div class="p-3 shadow-sm rounded bg-light">
                                <strong>{{ $label }}:</strong><br>
                                <span class="text-secondary">{{ ucwords($complain->$col) }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>




            {{-- ===================== SECTION 2 ====================== --}}
            <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">
                Entity
            </h5>

            @php
                $infoFields = [
                    'type_of_complaint' => 'Type of Complaint',
                    'complaint_type' => 'Information Type',
                    'involved_type' => 'Involved Type',

                    'gst_description' => 'GST Description',
                    'location' => 'Location',
                    'gst_address1' => 'Address 1',
                    'gst_address2' => 'Address 2',
                    'district_name' => 'District Name',
                    'pincode' => 'Pincode',
                    'gst_firm_name' => 'Firm Name',
                    'gst_firm_address' => 'Firm Address',
                    'gst_gstin' => 'GSTIN',



                    'gst_person_name' => 'Person Name',
                    'gst_locality' => 'Locality',
                    'gst_city' => 'City',
                    'gst_vehicle_number' => 'Vehicle Number',

                    'vat_firm_name' => 'VAT Firm Name',
                    'vat_tin' => 'VAT TIN',
                    'vat_vehicle_number' => 'VAT Vehicle Number',
                    'vat_firm_address' => 'VAT Firm Address',
                    'vat_person_name' => 'VAT Person Name',
                    'vat_locality' => 'VAT Locality',
                    'vat_city' => 'VAT City',
                    'vat_description' => 'VAT Description',

                    'excise_name' => 'Excise Name',
                    'excise_city' => 'Excise City',
                    'excise_place' => 'Place',
                    'excise_time' => 'Time',
                    'excise_vehicle_number' => 'Vehicle Number',
                    'excise_desc' => 'Description',
                    'excise_details' => 'Details',
                ];
            @endphp

            <div class="row g-3 mb-4">
                @foreach ($infoFields as $col => $label)
                    @if (!empty($complain->$col))
                        <div class="col-md-4">
                            <div class="p-3 shadow-sm rounded bg-light">
                                <strong>{{ $label }}:</strong><br>
                                <span class="text-secondary">{{ ucwords($complain->$col) }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if ($complain->created_at)
                    <div class="col-md-4">
                        <div class="p-3 shadow-sm rounded bg-light">
                            <strong>Information Date:</strong> {{ $complain->created_at->format('d-m-Y') }}
                        </div>
                    </div>
                @endif

            </div>


            {{-- ===================== DOCUMENTS ====================== --}}
            @if ($complain->gst_proof || $complain->vat_proof || $complain->excise_proof)
                <hr>
                <h5 class="fw-semibold text-secondary mb-3">📎 Attached Documents</h5>
                <div class="row g-3">
                    @foreach (['gst_proof' => 'GST', 'vat_proof' => 'VAT', 'excise_proof' => 'Excise'] as $key => $label)
                        @if ($complain->$key)
                            @foreach (json_decode($complain->$key, true) as $file)
                                <div class="col-md-4">
                                    <a href="{{ asset('storage/complaints/' . $complain->application_id . '/' . $file) }}"
                                        target="_blank" class="btn btn-outline-primary w-100 text-center">
                                        View {{ $label }} Proof
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @endif


            {{-- BACK BUTTON --}}
            <div class="mt-4 text-center">
                <a href="{{ route('user.dashboard') }}" class="btn btn-danger px-4">
                    ← Back to Dashboard
                </a>
            </div>

        </div>
    </div>

@endsection
