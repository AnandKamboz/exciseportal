@extends('detc.layouts.dashboard')

@section('content')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <div class="container mt-4">

        <h3 class="mb-3">All Applications</h3>
        <table id="applicationsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Type Of Information</th>
                    <th>Submitted On</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>{{ $app->application_id }}</td>
                        <td>{{ $app->type_of_complaint }}</td>
                        <td>{{ $app->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('detc.application.view', $app->secure_id) }}" class="btn btn-primary btn-sm">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    {{-- <tr>
                        <td colspan="5" class="text-center">No Records Found</td>
                    </tr> --}}
                @endforelse
            </tbody>
        </table>

    </div>

    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable({
                "pageLength": 10,
                "ordering": true,
                "searching": true,
                "lengthChange": true
            });
        });
    </script>
@endsection
