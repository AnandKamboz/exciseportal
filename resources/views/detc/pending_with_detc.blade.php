@extends('detc.layouts.dashboard')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        .page-wrapper {
            background: #f1f5f9;
            padding: 24px;
            border-radius: 18px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(6px);
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .glass-header {
            background: linear-gradient(135deg, #0f172a, #334155);
            color: #fff;
            padding: 18px 26px;
        }

        .glass-header h5 {
            font-weight: 600;
            letter-spacing: .4px;
        }

        table.dataTable thead th {
            background: #e2e8f0;
            color: #0f172a;
            font-weight: 600;
            text-align: center;
        }

        table.dataTable tbody td {
            text-align: center;
            vertical-align: middle;
            font-size: 14px;
        }

        .sn-circle {
            width: 32px;
            height: 32px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .btn-view {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            border: none;
            color: #fff;
            border-radius: 30px;
            padding: 4px 20px;
            font-size: 13px;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #fff;
        }

        .table-hover tbody tr:hover {
            background: #f8fafc;
            transition: .2s;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 30px;
            padding: 6px 14px;
            border: 1px solid #cbd5f5;
        }
    </style>

    <div class="container-fluid mt-4 page-wrapper">

        <div class="glass-card">

            {{-- HEADER --}}
            <div class="glass-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">🕒 Pending with DETC</h5>
                <span class="badge bg-light text-dark px-3">
                    Total : {{ count($applications) }}
                </span>
            </div>

            {{-- BODY --}}
            <div class="p-4 bg-white">

                <div class="table-responsive">
                    <table id="detcTable" class="table table-bordered table-hover align-middle">
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
                            @foreach ($applications as $index => $app)
                                <tr>
                                    <td>
                                        <span class="sn-circle">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>

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
            $('#detcTable').DataTable({
                pageLength: 5,
                ordering: true,
                searching: true,
                lengthChange: false
            });
        });
    </script>
@endsection
