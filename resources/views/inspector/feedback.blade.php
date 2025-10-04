@extends('users.layouts.dashboard')

@section('title', 'Inspection Report')
@section('page_title', 'Inspection Report')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="container mt-4 mb-5">
  <div class="card shadow-sm p-4">
    <h4 class="fw-bold text-primary mb-4">Inspection Report</h4>

    <form id="inspectionForm" enctype="multipart/form-data">
      @csrf

      <div class="row g-3">
        <!-- Source Grading -->
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

        <!-- Information Grading -->
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

        <!-- Process Complaint -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Process Complaint <span class="text-danger">*</span></label>
          <select name="process_complain" id="process_complain" class="form-select" required>
            <option value="" selected disabled>Select Action</option>
            <option value="accept">Accept</option>
            <option value="reject">Reject</option>
          </select>
        </div>

        <!-- Remarks -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>
          <textarea name="remarks" id="remarks" rows="1" class="form-control" placeholder="Enter remarks..." required></textarea>
        </div>
      </div>

      <!-- Accept Section -->
      <div id="acceptSection" class="mt-4 d-none">
        <div class="row g-3">
          <!-- Proposed Action -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Proposed Action <span class="text-danger">*</span></label>
            <select name="proposed_action" id="proposed_action" class="form-select">
              <option value="" selected disabled>Select Action</option>
              <option value="conduct raid">Conduct Raid</option>
              <option value="search action">Search Action</option>
            </select>
          </div>

          <!-- Commodities Reported -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Commodities Reported <span class="text-danger">*</span></label>
            <input type="text" name="commodities_reported" id="commodities_reported" class="form-control" placeholder="Enter commodities..." />
          </div>

          <!-- Estimated Value -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Estimated Value <span class="text-danger">*</span></label>
            <input type="number" name="estimated_value" id="estimated_value" class="form-control" placeholder="Enter estimated value..." />
          </div>

          <!-- Place Where Goods Are to Be Searched (full-width textarea) -->
          <div class="col-12">
            <label class="form-label fw-semibold">Place Where Goods Are to Be Searched <span class="text-danger">*</span></label>
            <textarea name="place_to_search" id="place_to_search" rows="3" class="form-control" placeholder="Enter place details..."></textarea>
          </div>

          <!-- Upload File (full-width) -->
          <div class="col-12">
            <label class="form-label fw-semibold">Upload File <span class="text-danger">*</span></label>
            <input type="file" name="upload_file" id="upload_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-proceed px-4">
          <i class="bi bi-check-circle me-1"></i> Submit Report
        </button>
      </div>

    </form>
  </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

  // Toggle accept section based on Process Complaint
  $('#process_complain').on('change', function() {
    if ($(this).val() === 'accept') {
      $('#acceptSection').removeClass('d-none');
    } else {
      $('#acceptSection').addClass('d-none');
    }
  });

  // AJAX form submission
  $('#inspectionForm').on('submit', function(e) {
    e.preventDefault();

    // Basic validation
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

    // AJAX submission
    let formData = new FormData(this);

    $.ajax({
      url: "",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        Swal.fire('Success!', 'Inspection report submitted successfully.', 'success');
        $('#inspectionForm')[0].reset();
        $('#acceptSection').addClass('d-none');
      },
      error: function(xhr) {
        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
      }
    });

  });
});
</script>
@endsection
