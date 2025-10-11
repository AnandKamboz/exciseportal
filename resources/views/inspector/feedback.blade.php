@extends('users.layouts.dashboard')

@section('title', 'Inspection Report')
@section('page_title', 'Inspection Report')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  @if (isset($inspectionReport) && $inspectionReport)
    <!-- <div class="container mt-4 mb-5">
      <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-4">Inspection Report Details</h4>

        <div class="row g-3">
          <div class="col-md-6">
            <p><strong>Source Grading:</strong> {{ ucfirst($inspectionReport->source_grading) }}</p>
            <p><strong>Information Grading:</strong> {{ ucfirst($inspectionReport->information_grading) }}</p>
            <p><strong>Process Complaint:</strong> {{ ucfirst($inspectionReport->process_complain) }}</p>
            <p><strong>Remarks:</strong> {{ $inspectionReport->remarks }}</p>
          </div>

          @if($inspectionReport->process_complain === 'accept')
          <div class="col-md-6">
            <p><strong>Proposed Action:</strong> {{ ucfirst($inspectionReport->proposed_action) }}</p>
            <p><strong>Commodities Reported:</strong> {{ $inspectionReport->commodities_reported }}</p>
            <p><strong>Estimated Value:</strong> ₹{{ number_format($inspectionReport->estimated_value, 2) }}</p>
            <p><strong>Place Where Goods Are to Be Searched:</strong> {{ $inspectionReport->place_to_search }}</p>
            <p><strong>Uploaded File:</strong>
                @if($inspectionReport->upload_file)
                    <a href="{{ asset('storage/' . $inspectionReport->upload_file) }}" target="_blank" class="btn btn-sm btn-primary">View File</a>
                @else
                    <span class="text-muted">No File</span>
                @endif
            </p>
          </div>
          @endif
      </div>
    </div> -->
    <div class="container mt-4 mb-5">
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-4">Inspection Report Details</h4>

        <div class="row g-4">
            <div class="col-md-6">
                <p class="mb-2"><strong>Source Grading:</strong> {{ ucfirst($inspectionReport->source_grading) }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Information Grading:</strong> {{ ucfirst($inspectionReport->information_grading) }}</p>
            </div>

            <div class="col-md-6">
                <p class="mb-2"><strong>Complaint Status:</strong> {{ ucfirst($inspectionReport->process_complain) }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Remarks:</strong> {{ $inspectionReport->remarks }}</p>
            </div>

            @if($inspectionReport->process_complain === 'accept')
                <div class="col-md-6">
                    <p class="mb-2"><strong>Proposed Action:</strong> {{ ucfirst($inspectionReport->proposed_action) }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Commodities Reported:</strong> {{ $inspectionReport->commodities_reported }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-2"><strong>Estimated Value:</strong> ₹{{ number_format($inspectionReport->estimated_value, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Place Where Goods Are to Be Searched:</strong> {{ $inspectionReport->place_to_search }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-2"><strong>Uploaded File:</strong></p>
                    @if($inspectionReport->upload_file)
                        <a href="{{ asset('storage/' . $inspectionReport->upload_file) }}" target="_blank" class="btn btn-sm btn-primary">View File</a>
                    @else
                        <span class="text-muted">No File</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Optional card footer at bottom --}}
        <div class="card-footer text-end bg-white border-0 mt-4">
            <small class="text-muted">Report generated on: {{ $inspectionReport->created_at->format('d-m-Y H:i') }}</small>
        </div>
    </div>
</div>

@else
    <div class="container mt-4 mb-5">
      <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-4">Inspection Report</h4>

        <form id="inspectionForm" enctype="multipart/form-data">
          @csrf

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Source Grading <span class="text-danger">*</span></label>
              <select name="source_grading" id="source_grading" class="form-select" required>
                <option value="" selected disabled>Select Source Grading</option>
                <option value="completely reliable">Completely Reliable</option>
                <option value="usually reliable">Usually Reliable</option>
                <option value="fairly reliable">Fairly Reliable</option>
                <option value="not usually reliable">Not Usually Reliable</option>
                <option value="unreliable">Unreliable</option>
                <option value="reliability unknown">Reliability Unknown</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Information Grading <span class="text-danger">*</span></label>
              <select name="information_grading" id="information_grading" class="form-select" required>
                <option value="" selected disabled>Select Information Grading</option>
                <option value="confirmed">Confirmed</option>
                <option value="probably true">Probably True</option>
                <option value="possibly true">Possibly True</option>
                <option value="doubtfully true">Doubtfully True</option>
                <option value="improbable report">Improbable Report</option>
                <option value="truth cannot be judged">Truth Cannot Be Judged</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Process Complaint <span class="text-danger">*</span></label>
              <select name="process_complain" id="process_complain" class="form-select" required>
                <option value="" selected disabled>Select Action</option>
                <option value="accept">Complaint is found genuine</option>
                <option value="reject">Non-genuine/ Fake complaint</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>
              <textarea name="remarks" id="remarks" rows="1" class="form-control" placeholder="Enter remarks..." required></textarea>
            </div>

            
          </div>

          <div id="acceptSection" class="mt-4 d-none">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Proposed Action <span class="text-danger">*</span></label>
                <select name="proposed_action" id="proposed_action" class="form-select">
                  <option value="" selected disabled>Select Action</option>
                  <option value="conduct raid">Conduct Raid</option>
                  <option value="search action">Search Operations</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Commodities Suspected <span class="text-danger">*</span></label>
                <input type="text" name="commodities_reported" id="commodities_reported" class="form-control" placeholder="Enter commodities..." />
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Estimated Value <span class="text-danger">*</span></label>
                <input type="number" name="estimated_value" id="estimated_value" class="form-control" placeholder="Enter estimated value..." />
              </div>

               <div class="col-6">
                <label class="form-label fw-semibold">Upload File <span class="text-danger">*</span></label>
                <input type="file" name="upload_file" id="upload_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Place where goods are suspected to be secreted <span class="text-danger">*</span></label>
                <textarea name="place_to_search" id="place_to_search" rows="3" class="form-control" placeholder="Enter place details..."></textarea>
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <!-- <button type="submit" class="btn btn-proceed px-4">
              <i class="bi bi-check-circle me-1"></i> Submit Report
            </button> -->
            <button type="submit" class="btn btn-proceed px-4">
    <i class="bi bi-check-circle me-1"></i> Submit Report
</button>

          </div>

        </form>
      </div>
    </div>
@endif

<style>
  .btn-proceed {
    background-color: #0d6efd; /* Bootstrap primary color */
    color: #fff;               /* Text color white */
    border: 1px solid #0d6efd;
}

.btn-proceed:hover {
    background-color: #0b5ed7; /* Thicker shade on hover */
    border-color: #0b5ed7;
}

</style>



<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// $(document).ready(function() {

//   // Toggle accept section based on Process Complaint
//   $('#process_complain').on('change', function() {
//     if ($(this).val() === 'accept') {
//       $('#acceptSection').removeClass('d-none');
//     } else {
//       $('#acceptSection').addClass('d-none');
//     }
//   });

//   // AJAX form submission
//   $('#inspectionForm').on('submit', function(e) {
//     e.preventDefault();

//     // Basic validation
//     const process = $('#process_complain').val();
//     const remarks = $('#remarks').val().trim();

//     if (!$('#source_grading').val() || !$('#information_grading').val() || !process || !remarks) {
//       Swal.fire('Missing Information', 'Please fill all required fields.', 'warning');
//       return;
//     }

//     if (process === 'accept') {
//       if (
//         !$('#proposed_action').val() ||
//         !$('#commodities_reported').val().trim() ||
//         !$('#estimated_value').val().trim() ||
//         !$('#place_to_search').val().trim() ||
//         !$('#upload_file').val()
//       ) {
//         Swal.fire('Incomplete Details', 'Please complete all fields for accepted complaint.', 'warning');
//         return;
//       }
//     }

//     // AJAX submission
//     let formData = new FormData(this);

//     $.ajax({
//       url: "",
//       method: "POST",
//       data: formData,
//       processData: false,
//       contentType: false,
//       success: function(response) {
//         Swal.fire('Success!', 'Inspection report submitted successfully.', 'success');
//         $('#inspectionForm')[0].reset();
//         $('#acceptSection').addClass('d-none');
//       },
//       error: function(xhr) {
//         Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
//       }
//     });

//   });
// });

// $(document).ready(function() {

//   $('#process_complain').on('change', function() {
//     if ($(this).val() === 'accept') {
//       $('#acceptSection').removeClass('d-none');
//     } else {
//       $('#acceptSection').addClass('d-none');
//       $('#acceptSection').find('input, textarea, select').val('');
//     }
//   });

//   $('#inspectionForm').on('submit', function(e) {
//     e.preventDefault();

//     const process = $('#process_complain').val();
//     const remarks = $('#remarks').val().trim();

//     if (!$('#source_grading').val() || !$('#information_grading').val() || !process || !remarks) {
//       Swal.fire('Missing Information', 'Please fill all required fields.', 'warning');
//       return;
//     }

//     if (process === 'accept') {
//       if (
//         !$('#proposed_action').val() ||
//         !$('#commodities_reported').val().trim() ||
//         !$('#estimated_value').val().trim() ||
//         !$('#place_to_search').val().trim() ||
//         !$('#upload_file').val()
//       ) {
//         Swal.fire('Incomplete Details', 'Please complete all fields for accepted complaint.', 'warning');
//         return;
//       }
//     }

//     let formData = new FormData(this);

//     $.ajax({
//       url: "{{ route('inspector.action') }}", 
//       method: "POST",
//       data: formData,
//       processData: false,
//       contentType: false,
//       success: function(response) {
//         Swal.fire('Success!', 'Inspection report submitted successfully.', 'success');
//         $('#inspectionForm')[0].reset();
//         $('#acceptSection').addClass('d-none');
//       },
//       error: function(xhr) {
//         Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
//       }
//     });

//   });
// });

$(document).ready(function() {

  // Toggle accept section
  $('#process_complain').on('change', function() {
    if ($(this).val() === 'accept') {
      $('#acceptSection').removeClass('d-none');
    } else {
      $('#acceptSection').addClass('d-none');
      $('#acceptSection').find('input, textarea, select').val('');
    }
  });

  // Form submission
  $('#inspectionForm').on('submit', function(e) {
    e.preventDefault();

    const process = $('#process_complain').val();
    const remarks = $('#remarks').val().trim();

    if (!$('#source_grading').val() || !$('#information_grading').val() || !process || !remarks) {
      Swal.fire('Missing Information', 'Please fill all required fields.', 'warning');
      return;
    }

    if (process === 'accept') {
      if (
        !$('#proposed_action').val() ||
        !$('#commodities_reported').val().trim() ||
        !$('#estimated_value').val().trim() ||
        !$('#place_to_search').val().trim() ||
        !$('#upload_file').val()
      ) {
        Swal.fire('Incomplete Details', 'Please complete all fields for accepted complaint.', 'warning');
        return;
      }
    }

    let formData = new FormData(this);
    const urlParams = new URLSearchParams(window.location.search);
    const secure_id = urlParams.get('secure_id');

    if (secure_id) {
      formData.append('secure_id', secure_id);
    }

    $.ajax({
      url: "{{ route('inspector.action') }}", 
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
    //   success: function(response) {
    //     Swal.fire('Success!', 'Inspection report submitted successfully.', 'success');
    //     $('#inspectionForm')[0].reset();
    //     $('#acceptSection').addClass('d-none');
    //    window.location.reload();
    //   },
    success: function(response) {
        Swal.fire('Success!', 'Inspection report submitted successfully.', 'success').then(() => {
            // Reset form
            $('#inspectionForm')[0].reset();
            $('#acceptSection').addClass('d-none');

            // Reload the current page
            window.location.reload();
        });
    },

      error: function(xhr) {
        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
      }
    });

  });

});

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

</script>
@endsection
