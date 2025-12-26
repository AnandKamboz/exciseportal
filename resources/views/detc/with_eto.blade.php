@extends('detc.layouts.dashboard')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        .page-wrapper {
            background: #f8fafc;
            padding: 18px;
            border-radius: 14px;
        }

        .custom-card {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        .custom-header {
            background: linear-gradient(135deg, #0ea5e9, #0369a1);
            color: #fff;
            padding: 16px 22px;
        }

        .custom-header h5 {
            font-weight: 600;
            letter-spacing: .3px;
        }

        table.dataTable thead th {
            background: #ecfeff;
            font-weight: 600;
            color: #075985;
            text-align: center;
        }

        table.dataTable tbody td {
            text-align: center;
            vertical-align: middle;
        }

        .btn-view {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            color: #fff;
            border-radius: 20px;
            padding: 4px 16px;
            font-size: 13px;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #fff;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 20px;
            padding: 6px 12px;
            border: 1px solid #d1d5db;
        }

        .table-hover tbody tr:hover {
            background: #f0f9ff;
            transition: .2s;
        }
    </style>

    <div class="container-fluid mt-4 page-wrapper">

        <div class="card custom-card border-0">

            {{-- HEADER --}}
            <div class="custom-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📤 Applications With ETO</h5>
                <span class="badge bg-dark">
                    Total : {{ count($applications) }}
                </span>
            </div>

            {{-- BODY --}}
            <div class="card-body bg-white">

                <div class="table-responsive">
                    <table id="etoTable" class="table table-bordered table-hover align-middle">
                        <thead>
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
                                        {{ ucwords(str_replace('_', ' ', $app->type_of_complaint ?? 'N/A')) }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('detc.application.view', $app->secure_id) }}"
                                            class="btn btn-view btn-sm">
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
            $('#etoTable').DataTable({
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
