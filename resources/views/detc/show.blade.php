@extends('users.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Complaint Details')

@section('content')

    @php
        $entityState = 'Haryana';
        $entityDistrict = null;

        if ($complain->complainant_district) {
            $entityDistrict = \App\Models\District::where('id', $complain->complainant_district)->value('name');
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
            color: #374151;
            font-weight: 600;
        }

        .value-text {
            font-size: 15px;
            font-weight: 500;
            color: #111;
        }

        .btn-primary {
            background: #0a3d62;
            border: none;
        }

        .btn-back {
            background: #e74c3c;
            border: none;
        }

        .btn-back:hover {
            background: #c0392b;
        }

        /* STAR ON ALL REQUIRED LABELS */
        .required:after {
            content: " *";
            color: red;
            font-weight: bold;
        }
    </style>

    <div class="container-fluid">

        <div class="card p-4 mb-4 shadow-sm border-0" style="background:#fafafa;">

            <div class="section-head">Information Details</div>

            {{-- ================= INFORMER DETAILS ================= --}}
            <div class="section-head mt-3">Informer Details</div>

            <div class="row g-3">
                @php $info=['application_id'=>'Application ID']; @endphp

                @foreach ($info as $col => $label)
                    @if ($complain->$col)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">{{ $label }}</div>
                                <div class="value-text">{{ $complain->$col }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="col-md-4">
                    <div class="info-card">
                        <div class="label-text">Date of Information</div>
                        <div class="value-text">{{ $complain->created_at->format('d-m-Y') }}</div>
                    </div>
                </div>
            </div>

            <hr>

            {{-- ================= ENTITY DETAILS ================= --}}
            <div class="section-head">Entity Details</div>

            <div class="row g-3">

                @php
                    $fields = [
                        'type_of_complaint',
                        'gst_description',
                        'complainant_district',
                        'pincode',
                        'involved_type',
                        'gst_firm_name',
                        'gst_gstin',
                        'gst_firm_address',
                        'location',
                        'gst_address2',
                        'gst_locality',
                        'gst_city',
                        'gst_vehicle_number',
                        'vat_firm_name',
                        'vat_tin',
                        'vat_firm_address',
                        'vat_person_name',
                        'vat_vehicle_number',
                        'vat_description',
                        'excise_name',
                        'excise_place',
                        'excise_time',
                        'excise_vehicle_number',
                        'excise_desc',
                        'excise_details',
                    ];

                    $labels = collect($fields)
                        ->mapWithKeys(fn($i) => [$i => ucwords(str_replace('_', ' ', $i))])
                        ->toArray();
                @endphp

                @foreach ($labels as $col => $label)
                    @if ($col == 'complainant_district')
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">{{ $label }}</div>
                                <div class="value-text">{{ $entityDistrict ?? 'N/A' }}</div>
                            </div>
                        </div>
                        @continue
                    @endif

                    @if ($complain->$col)
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="label-text">{{ $label }}</div>
                                <div class="value-text">
                                    {{ ucwords(str_replace('_', ' ', $complain->$col)) }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- DOCUMENTS --}}
            @if ($complain->gst_proof || $complain->vat_proof || $complain->excise_proof)
                <hr>
                <div class="section-head">Attached Documents</div>

                <div class="row g-3">
                    @foreach (['gst_proof' => 'GST', 'vat_proof' => 'VAT', 'excise_proof' => 'Excise'] as $key => $label)
                        @if ($complain->$key)
                            @foreach (json_decode($complain->$key, true) as $file)
                                <div class="col-md-4">
                                    <a href="{{ asset('storage/complaints/' . $complain->application_id . '/' . $file) }}"
                                        target="_blank" class="btn btn-outline-secondary w-100">
                                        View {{ $label }}
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @endif

            <hr>

            {{-- PROCEED BUTTON --}}
            <div id="proceedSection" class="text-center my-4">
                <button class="btn btn-primary btn-lg px-4" id="proceedBtn">Proceed</button>
            </div>


            {{-- ACTION FORM --}}
            <div id="actionForm" class="p-4 rounded bg-white shadow-sm border" style="display:none;">
                <h5 class="mb-4" style="font-weight:600;">DETC Action Panel</h5>

                <form>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold required">Proposed Action</label>
                            <select class="form-select" id="proposedAction" required>
                                <option value="">Select</option>
                                <option value="actionable">Actionable</option>
                                <option value="non_actionable">Non Actionable</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="actionableBox" style="display:none;">
                            <label class="form-label fw-semibold required">Select Action</label>
                            <select class="form-select">
                                <option value="">Select</option>
                                <option value="action_taken">Action Taken</option>
                                <option value="tax_evasion">Tax Evasion</option>
                                <option value="any_other">Any Other</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="nonActionableBox" style="display:none;">
                            <label class="form-label fw-semibold required">Reason</label>
                            <select class="form-select">
                                <option value="">Select</option>
                                <option value="information_incomplete">Information Incomplete</option>
                                <option value="false_information">False Information</option>
                                <option value="any_other">Any Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold required">Remarks</label>
                        <textarea class="form-control" rows="3" required></textarea>
                    </div>

                    <button class="btn btn-primary mt-3 px-4">Submit</button>
                </form>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('detc.dashboard') }}" class="btn btn-back px-4">
                    ← Back to Dashboard
                </a>
            </div>

        </div>
    </div>


    <script>
        document.getElementById('proceedBtn').onclick = () => {
            document.getElementById('proceedSection').style.display = 'none';
            document.getElementById('actionForm').style.display = 'block';
        };

        document.getElementById('proposedAction').onchange = function() {
            let act = document.getElementById('actionableBox');
            let non = document.getElementById('nonActionableBox');

            if (this.value === 'actionable') {
                act.style.display = 'block';
                non.style.display = 'none';
            } else if (this.value === 'non_actionable') {
                non.style.display = 'block';
                act.style.display = 'none';
            } else {
                act.style.display = 'none';
                non.style.display = 'none';
            }
        }

        document.getElementById('proposedAction').onchange = function() {

            let act = document.getElementById('actionableBox');
            let non = document.getElementById('nonActionableBox');

            let actionSelect = act.querySelector('select');
            let reasonSelect = non.querySelector('select');

            if (this.value === 'actionable') {
                act.style.display = 'block';
                non.style.display = 'none';
                reasonSelect.value = '';
            } else if (this.value === 'non_actionable') {
                non.style.display = 'block';
                act.style.display = 'none';
                actionSelect.value = '';
            } else {
                act.style.display = 'none';
                non.style.display = 'none';
                actionSelect.value = '';
                reasonSelect.value = '';
            }
        };
    </script>
@endsection
