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
                <p><strong>Complainant Name:</strong> {{ ucfirst($complain->complainant_name) }}</p>
                <p><strong>Phone:</strong> {{ $complain->complainant_phone }}</p>
                <p><strong>Email:</strong> {{ $complain->complainant_email }}</p>
                <p><strong>Aadhaar:</strong> {{ $complain->complainant_aadhaar }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Complainant District:</strong> {{ $complainantDistrictName }}</p>
                <p><strong>Against District ID:</strong> {{ $againstDistrictId }}</p>
                <p><strong>Complaint Type:</strong> {{ strtoupper($complain->complaint_type) }}</p>



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
                <p><strong>Firm Name:</strong> {{ ucfirst($complain->firm_name) }}</p>
                <p><strong>GSTIN:</strong> {{ $complain->gstin }}</p>
                <p><strong>Firm Address:</strong> {{ ucfirst($complain->firm_address) }}</p>
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


<!-- Form Here -->
   <hr>
    @if($complain->detc_status == null)
        <div class="card p-4 mb-4" style="background: #FFF8F0;">
            <h5 class="fw-bold text-primary mb-3">Update Complaint Status</h5>
            <form action="{{ route('user.updateComplaintStatus', $complain->secure_id) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label"><strong>Status</strong></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="" disabled selected>Select Action</option>
                            <option value="forward_to_inspector">Forward to Inspector</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="remarks" class="form-label"><strong>Remarks</strong></label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks here..." required></textarea>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                </div>
            </form>
        </div>
    @else
        <div class="card shadow-sm mb-3" style="border-radius: 10px; background: #FFF9F3;">
                <div class="card-header py-2" style="background: #FFDAB9; border-radius: 10px 10px 0 0;">
                    <h6 class="mb-0 fw-bold text-primary">DETC Observation</h6>
                </div>

                <div class="card-body py-2 px-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status:</strong></p>
                            @if($complain->detc_status == 'forward_to_inspector')
                                <span class="badge bg-info text-dark py-1 px-2" style="font-size: 0.85rem; border-radius: 6px;">
                                    Forward to Inspector
                                </span>
                            @elseif($complain->detc_status == 'rejected')
                                <span class="badge bg-danger py-1 px-2" style="font-size: 0.85rem; border-radius: 6px;">
                                    Rejected
                                </span>
                            @else
                                <span class="badge bg-secondary py-1 px-2" style="font-size: 0.85rem; border-radius: 6px;">
                                    Unknown
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1"><strong>Remarks:</strong></p>
                            <div class="p-2" style="background: #FFF3E0; border-radius: 6px; min-height: 50px; font-size: 0.9rem;">
                                {{ $complain->detc_remarks ?? 'No remarks provided' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif

    <hr>
    <!-- <div class="text-center">
        <button type="button" class="btn btn-proceed mt-2 mb-2">
            <i class="bi bi-arrow-right-circle me-1"></i> Proceed
        </button>
    </div> -->

    <div class="text-center">
        <a href="{{ route('inspector.feedback') }}" class="btn btn-proceed mt-2 mb-2">
            <i class="bi bi-arrow-right-circle me-1"></i> Proceed
        </a>
    </div>

<style>
.btn-proceed {
  background: linear-gradient(135deg, #FF8A73, #FF3200);
  color: #fff;
  font-weight: 600;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(255, 100, 50, 0.3);
}
.btn-proceed:hover {
  background: linear-gradient(135deg, #FF3200, #FF8A73);
  transform: translateY(-2px);
  box-shadow: 0 6px 14px rgba(255, 100, 50, 0.4);
  color: #fff;
}
</style>




   

<!-- Include this at the bottom of your Blade, before </body> -->
    @if(session('success'))
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
    @endif



@endsection
