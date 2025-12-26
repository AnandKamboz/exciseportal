@extends('eto.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content') {{-- YEH LINE MISSING THI --}}

    <div class="container-fluid">

        <div class="card p-3">
            <h5 class="fw-bold mb-3">Complaint Details</h5>

            <div class="table-responsive">

                <style>
                    .table-card {
                        background: #ffffff;
                        border-radius: 14px;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                        padding: 15px;
                    }

                    .table thead th {
                        background: linear-gradient(135deg, #65A5C8FF, #7785D5FF);
                        color: #fff;
                        text-align: center;
                        font-weight: 600;
                        vertical-align: middle;
                    }

                    .table tbody td {
                        text-align: center;
                        vertical-align: middle;
                    }

                    .table-hover tbody tr:hover {
                        background-color: #fff7ed;
                        transition: 0.2s;
                    }

                    .badge {
                        padding: 6px 14px;
                        border-radius: 20px;
                        font-size: 12px;
                        font-weight: 600;
                    }

                    .btn-view {
                        background: linear-gradient(135deg, #2563eb, #1e40af);
                        border: none;
                        color: #fff;
                        border-radius: 20px;
                        padding: 4px 14px;
                        font-size: 13px;
                    }

                    .btn-view:hover {
                        background: linear-gradient(135deg, #1e40af, #1e3a8a);
                        color: #fff;
                    }
                </style>
                <div class="table-card">
                    <table id="complaintsTable" class="table table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Application ID</th>
                                <th class="text-center">Type of Information</th>
                                <th class="text-center">Date of Information</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">View</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($informations as $index => $information)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="fw-semibold text-primary">
                                        {{ $information->application_id }}
                                    </td>

                                    <td>
                                        {{ ucwords(str_replace('_', ' ', $information->type_of_complaint)) }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($information->created_at)->format('d M Y') }}
                                    </td>

                                    {{-- ✅ STATUS (LOGIC SAME) --}}
                                    <td>
                                        @php
                                            $etoAction = $information->latestEtoAction;
                                            $status = $etoAction->current_status ?? ($etoAction->status ?? 'pending');
                                            $reason = $etoAction->reason ?? null;
                                        @endphp

                                        @if (in_array($status, ['sent_to_hq', 'eto_report_uploaded']))
                                            <span class="badge bg-info">Sent to HQ</span>
                                        @elseif ($status === 'pending' && $reason === 'information_incomplete')
                                            <span class="badge bg-warning text-dark">
                                                Pending with Applicant
                                            </span>
                                        @elseif ($status === 'pending')
                                            <span class="badge bg-secondary">Pending</span>
                                        @elseif (in_array($status, ['closed', 'eto_non_actionable_closed']))
                                            <span class="badge bg-danger">Closed</span>
                                        @elseif (!$etoAction)
                                            <span class="badge bg-secondary">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ ucwords(str_replace('_', ' ', $status)) }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- VIEW --}}
                                    <td>
                                        <a href="{{ route('eto.information.view', $information->secure_id) }}"
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

@endsection
