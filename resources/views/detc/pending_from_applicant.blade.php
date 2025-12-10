@extends('detc.layouts.dashboard')

@section('content')
    <div class="container">
        <h3 class="mb-3">Pending From Applicant</h3>

        <table id="applicationsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Application ID</th>
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
                        <td colspan="5" class="text-center">No Records Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable();
        });
    </script>
@endsection
