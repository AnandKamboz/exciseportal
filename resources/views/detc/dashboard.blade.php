@extends('detc.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Total Cases</h5>
                    <h2>{{ $totalInformation }}</h2>
                    <a href="{{ route('detc.allApplications') }}" class="new-butt"> View All</a>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Forwarded to HQ</h5>
                    <h2>{{ $forwardedtoHq }}</h2>
                    <a href="{{ route('detc.forwarded.hq') }}" class="new-butt"> View All</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Forwarded to ETO</h5>
                    <h2>{{ $forwardedtoEto }}</h2>
                    <a href="{{ route('detc.forwarded.to.eto') }}" class="new-butt"> View All</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Pending with Applicant </h5>
                    <h2>{{ $pendingFromApplicant }}</h2>
                    <a href="{{ route('detc.pending.from.applicant') }}" class="new-butt"> View All</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Pending with DETC</h5>
                    <h2>{{ $pendingFromDetc }}</h2>
                    <a href="{{ route('detc.applications.pending_detc') }}" class="new-butt"> View All</a>
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
                @if (!empty($complain->returned_to_detc_at && $complain->reason == 'information_incomplete'))
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
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($allComplain as $index => $complain)
                            @php
                                $lastAction = \App\Models\DetcAction::where(
                                    'user_application_id',
                                    $complain->application_id,
                                )
                                    ->latest('id')
                                    ->first();

                                $status = 'No Action Yet';
                                $badge = 'bg-primary';

                                if ($lastAction) {
                                    if (
                                        $lastAction->reason == 'information_incomplete' &&
                                        $lastAction->send_to == 'applicant' &&
                                        $lastAction->applicant_submitted_at == null
                                    ) {
                                        $status = 'Pending with Applicant';
                                        $badge = 'bg-danger';
                                    }

                                    elseif (
                                        $lastAction->reason == 'information_incomplete' &&
                                        $lastAction->send_to == 'applicant' &&
                                        $lastAction->applicant_submitted_at != null
                                    ) {
                                        $status = 'Pending with DETC';
                                        $badge = 'bg-warning';
                                    }

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
                                    <a href="{{ route('detc.details', [$complain->secure_id]) }}"
                                        class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>



            </div>
        </div>
    </div>
@endsection
