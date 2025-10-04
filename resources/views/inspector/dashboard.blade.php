@extends('inspector.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Inspector Dashboard')

@section('content')
     <div class="container-fluid">
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3" style="background: #FF9983; color: #fff;">
                <h5>Total Complaints</h5>
                <h2>1</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3" style="background: #FF8A73; color: #fff;">
                <h5>Approved</h5>
                <h2>0</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3" style="background: #FFBDA3; color: #fff;">
                <h5>Pending</h5>
                <h2>1</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3" style="background: #FF6F5E; color: #fff;">
                <h5>Rejected</h5>
                <h2>0</h2>
            </div>
        </div>
    </div>

    <!-- DataTable -->
    <div class="card p-3">
        <h5 class="fw-bold text-danger mb-3">Recent Complaint Activities</h5>
        <div class="table-responsive">
            <table id="complaintsTable" class="table table-hover table-striped">
                <thead style="background: #FF8A73; color: #fff;">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>AAdhaar</th>
                        <th>Submission Date</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allComplain as $index => $complain)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ucfirst($complain->complainant_name) }}</td>
                        <td>{{ $complain->complainant_phone }}</td>
                        <td>{{ $complain->complainant_aadhaar }}</td>
                        <td>{{ $complain->created_at->format('d-m-Y') }}</td>
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