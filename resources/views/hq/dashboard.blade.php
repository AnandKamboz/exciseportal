@extends('hq.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
    <div class="container-fluid">

        {{-- <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Total</h5>
                <h2>{{ $informations->count() }}</h2>
                <a href="#" class="new-butt"> View All</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Pending</h5>
                <h2>{{ $informations->where('is_completed', 0)->count() }}</h2>
                <a href="#" class="new-butt"> View All</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Forwarded for Action</h5>
                <h2>{{ $informations->where('status', 'forward_to_inspector')->count() }}</h2>
                <a href="#" class="new-butt"> View All</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Rejected</h5>
                <h2>{{ $informations->where('status', 'rejected')->count() }}</h2>
                <a href="#" class="new-butt"> View All</a>
            </div>
        </div>
    </div> --}}

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
                    <h5>Pending From Applicant </h5>
                    <h2>{{ $pendingFromApplicant }}</h2>
                    <a href="{{ route('detc.pending.from.applicant') }}" class="new-butt"> View All</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Pending from DETC</h5>
                    <h2>{{ $pendingFromDetc }}</h2>
                    <a href="{{ route('detc.applications.pending_detc') }}" class="new-butt"> View All</a>
                </div>
            </div>
        </div>


        <div class="card p-3">
            <h5 class="fw-bold  mb-3">Information Details</h5>

            <div class="table-responsive">
                <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Date of Receiving</th>
                            <th>Application Id</th>
                            <th>Category</th>
                            <th>Type of Information</th>
                            {{-- <th>Status</th> --}}
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($informations as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->created_at->format('d-m-Y') }}</td>
                                <td>{{ $row->application_id }}</td>
                                <td>{{ ucfirst($row->complaint_type) }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $row->type_of_complaint)) }}</td>

                                {{-- <td>
                                @if ($row->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td> --}}

                                <td>
                                    <a href="{{ route('hq.details', [$row->secure_id]) }}"
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
