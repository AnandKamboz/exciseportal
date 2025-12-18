@extends('eto.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content') {{-- YEH LINE MISSING THI --}}

    <div class="container-fluid">

        <div class="card p-3">
            <h5 class="fw-bold mb-3">Complaint Details</h5>

            <div class="table-responsive">
                {{-- <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Application ID</th>
                            <th>Type of Information</th>
                            <th>Date on Information</th>
                            <th>View</th> 
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($informations as $index => $information)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $information->application_id }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $information->type_of_complaint)) }}</td>
                                <td>{{ \Carbon\Carbon::parse($information->created_at)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('eto.information.view', $information->secure_id) }}"
                                        class="btn btn-primary btn-sm text-center" style="padding: 4px 10px;">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table> --}}


                {{-- <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Application ID</th>
                            <th>Type of Information</th>
                            <th>Date on Information</th>
                            <th>View</th> 
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($informations as $index => $information)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $information->application_id }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $information->type_of_complaint)) }}</td>
                                <td>{{ \Carbon\Carbon::parse($information->created_at)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('eto.information.view', $information->secure_id) }}"
                                        class="btn btn-primary btn-sm text-center" style="padding: 4px 10px;">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table> --}}

                <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Application ID</th>
                            <th>Type of Information</th>
                            <th>Date on Information</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($informations as $index => $information)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>{{ $information->application_id }}</td>

                                <td>
                                    {{ ucwords(str_replace('_', ' ', $information->type_of_complaint)) }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($information->created_at)->format('d M Y') }}
                                </td>

                                {{-- ✅ STATUS --}}
                                <td>
                                    @php
                                        $etoAction = $information->latestEtoAction;

                                        $status = $etoAction->current_status ?? ($etoAction->status ?? 'pending');

                                        $reason = $etoAction->reason ?? null;
                                    @endphp

                                    {{-- Sent to HQ --}}
                                    @if (in_array($status, ['sent_to_hq', 'eto_report_uploaded']))
                                        <span class="badge bg-info">Sent to HQ</span>

                                        {{-- Pending with Applicant --}}
                                    @elseif ($status === 'pending' && $reason === 'information_incomplete')
                                        <span class="badge bg-warning text-dark">
                                            Pending with Applicant
                                        </span>

                                        {{-- Pending --}}
                                    @elseif ($status === 'pending')
                                        <span class="badge bg-secondary">Pending</span>

                                        {{-- Closed --}}
                                    @elseif (in_array($status, ['closed', 'eto_non_actionable_closed']))
                                        <span class="badge bg-danger">Closed</span>

                                        {{-- No Action Yet --}}
                                    @elseif (!$etoAction)
                                        <span class="badge bg-secondary">Pending</span>

                                        {{-- Fallback --}}
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </span>
                                    @endif
                                </td>


                                {{-- VIEW --}}
                                <td>
                                    <a href="{{ route('eto.information.view', $information->secure_id) }}"
                                        class="btn btn-primary btn-sm text-center" style="padding: 4px 10px;">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No records found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>



            </div>

        </div>

    </div>

@endsection
