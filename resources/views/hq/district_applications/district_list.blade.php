@extends('hq.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'District-wise Complaints')

@section('content')

    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                District-wise Information
            </div>

            <div class="card-body">
                <table id="districtTable" class="table table-bordered table-hover">
                    <thead class="table-dark text-center">
                        <tr class="text-center">
                            <th class="text-center">#</th>
                            <th class="text-center">District Name</th>
                            <th class="text-center">Total Applications</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($districtWiseApplications as $index => $district)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">
                                    {{ $district->district_name }}
                                </td>
                                <td>
                                    <span class="badge bg-info fs-6">
                                        {{ $district->total_applications }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('hq.district.complaints.list', $district->district_name) }}"
                                        class="btn btn-primary btn-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger fw-semibold">
                                    No data found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
            $('#districtTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 3]
                }]
            });
        });
    </script>
@endpush
