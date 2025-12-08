@extends('eto.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')   {{-- YEH LINE MISSING THI --}}

<div class="container-fluid">

    <div class="card p-3">
        <h5 class="fw-bold mb-3">Complaint Details</h5>

        <div class="table-responsive">
            <table id="complaintsTable" class="table table-hover table-striped">
                <thead style="background: #FF8A73; color: #fff;">
                    <tr>
                        <th>#</th>
                        <th>Application ID</th>
                        <th>Category</th>
                        {{-- <th>Type of Information</th> --}}
                        <th>Firm Name</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($informations as $index => $information)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $information->application_id }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $information->type_of_complaint)) }}</td>
                            <td>{{ $information->gst_firm_name }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
