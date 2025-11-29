@extends('hq.layouts.dashboard')

@section('title', 'Information Details')
@section('page_title', 'Information Details')

@section('content')

@php
    $hide = ['id','secure_id','district_id','is_completed','created_at','updated_at','user_id','gst_proof'];

    $informerFields = [
        'complainant_name',
        'complainant_phone',
        'complainant_email',
        'complainant_aadhar',
        'complainant_address',
        'complainant_address1',
        'complainant_address2',
        'complainant_state',
        'complainant_district',
    ];
@endphp

<style>
    .info-card{
        background:#fff;border:1px solid #e4e4e4;
        padding:14px;border-radius:6px;
    }
    .label{font-size:15px;font-weight:600;color:#374151;}
    .value{font-size:17px;font-weight:600;color:#111;}
</style>

<div class="container-fluid">
    <div class="card p-4 shadow-sm border-0" style="background:#fafafa;">

        {{-- APPLICATION ID --}}
        <div class="text-center mb-4">
            <h3 style="font-weight:700;font-size:22px;color:#0a3d62;">
                Application ID :
                <span style="color:#d35400;">
                    {{ ucfirst($information->application_id) }}
                </span>
            </h3>
        </div>


        {{-- ================= INFORMER DETAILS ================= --}}
        <h4 class="fw-bold mb-3">Informer Details</h4>

        <div class="row g-3">
            @foreach ($information->toArray() as $column => $value)

                @if(in_array($column,$hide)) @continue @endif
                @if(!in_array($column,$informerFields)) @continue @endif
                @if(empty($value)) @continue @endif

                {{-- SKIP array values for safety --}}
                @if(is_array($value)) @continue @endif

                {{-- Show State --}}
                @if ($column == 'complainant_state')
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label">State</div>
                            <div class="value">{{ $stateName ?? 'NA' }}</div>
                        </div>
                    </div>
                    @continue
                @endif

                {{-- Show District --}}
                @if ($column == 'complainant_district')
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="label">District</div>
                            <div class="value">{{ $districtName ?? 'NA' }}</div>
                        </div>
                    </div>
                    @continue
                @endif

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label">
                            {{ ucwords(str_replace(['complainant_','_'],' ',$column)) }}
                        </div>
                        <div class="value">
                            {{ ucwords(str_replace('_',' ',$value)) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <hr>

        {{-- ================= INFORMATION DETAILS ================= --}}
        <h4 class="fw-bold mb-3">Information Details</h4>

        <div class="row g-3">
            @foreach ($information->toArray() as $column => $value)

                @if(in_array($column,$hide)) @continue @endif
                @if(in_array($column,$informerFields)) @continue @endif
                @if(empty($value)) @continue @endif

                {{-- Avoid arrays --}}
                @if(is_array($value)) @continue @endif

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label">{{ ucwords(str_replace('_',' ',$column)) }}</div>
                        <div class="value">{{ ucwords(str_replace('_',' ',$value)) }}</div>
                    </div>
                </div>
            @endforeach
        </div>


        {{-- ================= DOCUMENTS ================= --}}
        @if ($information->gst_proof || $information->vat_proof || $information->excise_proof)
            <hr>
            <h4 class="fw-bold mb-3">Attached Documents</h4>

            <div class="row g-3">
                @foreach (['gst_proof'=>'GST','vat_proof'=>'VAT','excise_proof'=>'Excise'] as $key=>$label)
                    @if($information->$key)
                        @foreach(json_decode($information->$key,true) as $file)
                            <div class="col-md-4">
                                <a href="{{ asset('storage/complaints/'.$information->application_id.'/'.$file) }}"
                                   class="btn btn-outline-primary w-100" target="_blank">
                                   View {{ $label }}
                                </a>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        @endif


        {{-- ================= DETC ACTION ================= --}}
        @if ($detcAction)
            <hr>
            <h4 class="fw-bold mb-3 mt-3">DETC Action Taken</h4>

            <div class="row g-3">

                @if($detcAction->proposed_action)
                <div class="col-md-4">
                    <div class="info-card" style="border-left:4px solid #0a3d62;">
                        <div class="label">Proposed Action</div>
                        <div class="value">{{ ucwords(str_replace('_',' ',$detcAction->proposed_action)) }}</div>
                    </div>
                </div>
                @endif

                @if($detcAction->action_taken)
                <div class="col-md-4">
                    <div class="info-card" style="border-left:4px solid #28a745;">
                        <div class="label">Action Taken</div>
                        <div class="value">{{ ucwords(str_replace('_',' ',$detcAction->action_taken)) }}</div>
                    </div>
                </div>
                @endif

                @if($detcAction->reason)
                <div class="col-md-4">
                    <div class="info-card" style="border-left:4px solid #ffc107;">
                        <div class="label">Reason</div>
                        <div class="value">{{ ucwords(str_replace('_',' ',$detcAction->reason)) }}</div>
                    </div>
                </div>
                @endif

                <div class="col-md-4">
                    <div class="info-card" style="border-left:4px solid #6c757d;">
                        <div class="label">Remarks</div>
                        <div class="value">{{ $detcAction->remarks }}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-card" style="border-left:4px solid #17a2b8;">
                        <div class="label">Date</div>
                        <div class="value">{{ $detcAction->created_at->format('d-m-Y') }}</div>
                    </div>
                </div>
            </div>
        @endif


        <div class="text-center mt-4">
            <a href="{{ route('hq.dashboard') }}" class="btn btn-danger px-4">← Back</a>
        </div>

    </div>
</div>

@endsection
