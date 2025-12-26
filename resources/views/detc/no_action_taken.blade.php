@extends('detc.layouts.dashboard')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <div class="container-fluid mt-4">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">🚫 No Action Taken</h5>
                <span class="badge bg-dark">Total : {{ count($applications) }}</span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="noActionTable" class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">S.N.</th>
                                <th class="text-center">Application ID</th>
                                <th class="text-center">Type of Information</th>
                                <th class="text-center">Submitted On</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $index => $app)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="fw-semibold text-primary">
                                        {{ $app->application_id }}
                                    </td>

                                    <td>
                                        {{ ucwords(str_replace('_', ' ', $app->type_of_complaint)) }}
                                    </td>

                                    <td>
                                        {{ $app->created_at->format('d M Y') }}
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#noActionTable').DataTable({
                pageLength: 5,
                ordering: true,
                searching: true,
                lengthChange: false
            });
        });
    </script>
@endsection
