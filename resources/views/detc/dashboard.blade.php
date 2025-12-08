@extends('detc.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Total Information</h5>
                <h2>{{ $totalInformation }}</h2>
                <a href="#" class="new-butt"> View All</a>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Forwarded for Action</h5>
                <h2>{{ $forwardedForAction }}</h2>
                <a href="#" class="new-butt"> View All</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Not Actionable</h5>
                <h2>{{ $notActionable }}</h2>
                <a href="#" class="new-butt"> View All</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Pending Information</h5>
                <h2>{{ $pendingInformation }}</h2>
                <a href="" class="new-butt"> View All</a>
            </div>
        </div>
    </div>



    <!-- DataTable -->
    <div class="card p-3">
        <h5 class="fw-bold  mb-3">Complaint Details</h5>
        <div class="table-responsive">
            {{-- <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Date of Receiving</th>
                            <th>Application Id</th>
                            <th>Category</th>
                            <th>Type of Information</th>
                            <th>Issue Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allComplain as $index => $complain)
                            <tr>
                                <td>{{ $index + 1 }}</td>
            <td>{{ $complain->created_at->format('d-m-Y') }}</td>
            <td>{{ $complain->application_id }}</td>
            <td>{{ ucfirst($complain->complaint_type) }}</td>
            <td>{{ ucwords(str_replace('_', ' ', $complain->type_of_complaint)) }}</td>
            <td>
                @if(!empty($complain->returned_to_detc_at && $complain->reason == 'information_incomplete'))
                <span class="badge bg-danger">Pending</span>
                @else
                <span class="badge bg-success">Cleared</span>
                @endif
            </td>
            <td>
                <a href="{{ route('detc.details', [$complain->secure_id]) }}" class="btn btn-sm btn-primary">View</a>
            </td>
            </tr>
            @endforeach
            </tbody>
            </table> --}}

            <table id="complaintsTable" class="table table-hover table-striped">
                <thead style="background: #FF8A73; color: #fff;">
                    <tr>
                        <th>#</th>
                        <th>Date of Receiving</th>
                        <th>Application Id</th>
                        {{-- <th>Category</th> --}}
                        <th>Type of Information</th>
                        <th>Action Status</th>
                        <th>View</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($allComplain as $index => $complain)

                    @php
                    $lastAction = \App\Models\DetcAction::where('user_application_id', $complain->application_id)
                    ->latest('id')
                    ->first();

                    // Default
                    $status = 'No Action Yet';
                    $badge = 'bg-primary';

                    if ($lastAction) {

                    // 1️⃣ DETC ने information_incomplete भेजा लेकिन applicant ने अभी जवाब नहीं दिया
                    if (
                    $lastAction->reason == 'information_incomplete' &&
                    $lastAction->send_to == 'applicant' &&
                    $lastAction->applicant_submitted_at == null
                    ) {
                    $status = 'Pending From Applicant';
                    $badge = 'bg-danger';
                    }

                    // 2️⃣ Applicant ने missing info submit कर दी → अब DETC को लेना है action
                    elseif (
                    $lastAction->reason == 'information_incomplete' &&
                    $lastAction->send_to == 'applicant' &&
                    $lastAction->applicant_submitted_at != null
                    ) {
                    $status = 'Pending For DETC';
                    $badge = 'bg-warning';
                    }

                    // 3️⃣ DETC ने final action ले लिया
                    elseif ($lastAction->is_approved == 1) {
                    $status = 'Completed';
                    $badge = 'bg-success';
                    }
                    }
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $complain->created_at->format('d-m-Y') }}</td>
                        <td>{{ $complain->application_id }}</td>
                        {{-- <td>{{ ucfirst($complain->complaint_type) }}</td> --}}
                        <td>{{ ucwords(str_replace('_', ' ', $complain->type_of_complaint)) }}</td>
                        <td>
                            <span class="badge {{ $badge }}">{{ $status }}</span>
                        </td>

                        <td>
                            <a href="{{ route('detc.details', [$complain->secure_id]) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>



        </div>
    </div>
</div>
@endsection
