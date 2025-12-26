@extends('detc.layouts.dashboard')

@section('content')
    <div class="container-fluid mt-4">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-hourglass-split me-2"></i> Under Review Applications
                </h5>
                <span class="badge bg-dark">
                    Total : {{ count($applications) }}
                </span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="underReviewTable" class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Application ID</th>
                                <th>Applicant Name</th>
                                <th>Mobile</th>
                                <th>District</th>
                                <th>Missing Info Submitted On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($applications as $index => $app)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="fw-semibold text-primary">
                                        {{ $app->application_id }}
                                    </td>

                                    <td>{{ $app->complainant_name }}</td>

                                    <td>{{ $app->complainant_phone }}</td>

                                    <td>{{ $app->district_name ?? 'N/A' }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($app->missing_info_submitted_at)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            Under Review
                                        </span>
                                    </td>

                                    <td>
                                        <a href="{{ route('detc.application.view', $app->secure_id) }}"
                                            class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#underReviewTable').DataTable({
                pageLength: 10,
                lengthChange: true,
                ordering: true,
                searching: true
            });
        });
    </script>
@endpush
