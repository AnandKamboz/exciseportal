@extends('detc.layouts.dashboard')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        .page-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .page-title {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        table.dataTable thead th,
        table.dataTable tbody td {
            text-align: center !important;
            vertical-align: middle;
        }

        table.dataTable thead th {
            background-color: #f1f5f9;
            font-weight: 600;
        }

        .btn-view {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            color: #fff;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 13px;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #fff;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border-radius: 20px;
            padding: 5px 10px;
            border: 1px solid #d1d5db;
        }
    </style>

    <div class="container-fluid mt-4">

        {{-- HEADER --}}
        <div class="page-title">
            <h5 class="mb-0">📄 All Applications</h5>
        </div>

        {{-- TABLE CARD --}}
        <div class="page-card">
            <table id="applicationsTable" class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Application ID</th>
                        <th>Type of Information</th>
                        <th>Submitted On</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($applications as $key => $app)
                        <tr>
                            <td>{{ $key + 1 }}</td>

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
                                <a href="{{ route('detc.application.view', $app->secure_id) }}" class="btn btn-view btn-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable({
                pageLength: 5,
                ordering: true,
                searching: true,
                lengthChange: false,
                language: {
                    search: "🔍 Search:",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });
        });
    </script>
@endsection
