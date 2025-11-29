@extends('detc.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Total</h5>
                    <h2>1</h2>
                    <a href="#" class="new-butt"> View All</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Pending</h5>
                    <h2>1</h2>
                    <a href="#" class="new-butt"> View All</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Forwarded for Action</h5>
                    <h2>0</h2>
                    <a href="#" class="new-butt"> View All</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center p-3">
                    <h5>Rejected</h5>
                    <h2>0</h2>
                    <a href="#" class="new-butt"> View All</a>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="card p-3">
            <h5 class="fw-bold  mb-3">Complaint Details</h5>
            <div class="table-responsive">
                <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Date of Receiving</th>
                            <th>Application Id</th>
                            <th>Category</th>
                            <th>Type of Complaint</th>
                            <th>Status</th>
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
                                <td>{{ 'Pending' }}</td>
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
