@extends('eto.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content') {{-- YEH LINE MISSING THI --}}

    <div class="container-fluid">

        <div class="card p-3">
            <h5 class="fw-bold mb-3">Complaint Details</h5>

            {{-- <div class="table-responsive">
            <table id="complaintsTable" class="table table-hover table-striped">
                <thead style="background: #FF8A73; color: #fff;">
                    <tr>
                        <th>#</th>
                        <th>Application ID</th>
                        <th>Type of Information</th>
                        <th>Date on Information</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($informations as $index => $information)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $information->application_id }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $information->type_of_complaint)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($information->created_at)->format('d M Y') }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div> --}}

            <div class="table-responsive">
                <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Application ID</th>
                            <th>Type of Information</th>
                            <th>Date on Information</th>
                            <th>View</th> <!-- New column -->
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
                </table>
            </div>

        </div>

    </div>

@endsection
