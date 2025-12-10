@extends('detc.layouts.dashboard')

@section('content')
    <div class="container">

        <h3 class="mb-3">All Applications</h3>

        <table id="applicationsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Name</th>
                    <th>District</th>
                    <th>Complaint Type</th>
                    <th>Submitted On</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>{{ $app->application_id }}</td>
                        <td>{{ $app->complainant_name }}</td>
                        <td>{{ $app->district_name }}</td>
                        <td>{{ strtoupper($app->complaint_type) }}</td>
                        <td>{{ $app->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('detc.application.view', $app->secure_id) }}" class="btn btn-primary btn-sm">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Records Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Datatable CDN --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable();
        });
    </script>
@endsection
