@extends('users.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Complaint Details')

@section('content')
<div class="container-fluid">
    <div class="card p-4 mb-4" style="background: #FFFBF5;">
        <h4 class="fw-bold text-danger mb-3">Complaint Details</h4>

        <div class="row g-3">
            <div class="col-md-6">
                <p><strong>Complaint ID:</strong> {{ $complain->complaint_id }}</p>
                <p><strong>Complainant Name:</strong> {{ $complain->complainant_name }}</p>
                <p><strong>Phone:</strong> {{ $complain->complainant_phone }}</p>
                <p><strong>Email:</strong> {{ $complain->complainant_email }}</p>
                <p><strong>Aadhaar:</strong> {{ $complain->complainant_aadhaar }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>District ID:</strong> {{ $complain->complainant_dist_id }}</p>
                <p><strong>Against District ID:</strong> {{ $complain->against_district_id }}</p>
                <p><strong>Complaint Type:</strong> {{ $complain->complaint_type }}</p>
                <p><strong>Status:</strong>
                    @if($complain->is_completed)
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </p>
                <p><strong>Fraud Related:</strong> {{ $complain->is_fraud_related ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        <hr>

        <div class="row g-3">
            <div class="col-md-6">
                <p><strong>Firm Name:</strong> {{ $complain->firm_name }}</p>
                <p><strong>GSTIN:</strong> {{ $complain->gstin }}</p>
                <p><strong>Firm Address:</strong> {{ $complain->firm_address }}</p>
                <p><strong>Estimate Tax Amount:</strong> ₹{{ number_format($complain->estimate_tax_amount, 2) }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Remarks:</strong> {{ $complain->remarks }}</p>
                <p><strong>Upload Document:</strong>
                    @if($complain->upload_document)
                        <a href="{{ asset('storage/' . $complain->upload_document) }}" target="_blank" class="btn btn-sm btn-primary">View Document</a>
                    @else
                        <span class="text-muted">No Document</span>
                    @endif
                </p>
                <p><strong>Proof Document:</strong>
                    @if($complain->proof_document)
                        <a href="{{ asset('storage/' . $complain->proof_document) }}" target="_blank" class="btn btn-sm btn-secondary">View Proof</a>
                    @else
                        <span class="text-muted">No Proof</span>
                    @endif
                </p>
            </div>
        </div>

        <hr>

        <div class="row g-3">
            <div class="col-md-6">
                <p><strong>Created At:</strong> {{ $complain->created_at->format('d-m-Y H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $complain->updated_at->format('d-m-Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-danger">Back to Dashboard</a>
        </div>
    </div>
</div>

@endsection
