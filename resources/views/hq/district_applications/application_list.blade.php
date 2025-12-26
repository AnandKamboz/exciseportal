@extends('hq.layouts.dashboard')

@section('title', 'Applications List')
@section('page_title', 'District Applications')

@section('content')

    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white fw-semibold text-center">
                Applications – {{ $district_name }}
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="applicationsTable" class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Application ID</th>
                                <th class="text-center">District</th>
                                <th class="text-center">Type of Information</th>
                                <th class="text-center">Created Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($applications as $index => $app)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">
                                        {{ $app->application_id }}
                                    </td>
                                    <td>{{ ucfirst($app->district_name) }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $app->type_of_complaint)) }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($app->created_at)->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('hq.district.complaints.view', $app->secure_id) }}"
                                            class="btn btn-primary btn-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger fw-semibold">
                                        No applications found for this district
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 5]
                }]
            });
        });
    </script>
@endpush
