@extends('users.layouts.dashboard')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

    <div class="container-fluid">
        <!-- DataTable -->
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Information Details</h5>
                <a href="{{ route('complainant') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>
                    {{-- Add Complaint --}}
                    Add Information
                </a>
            </div>


            <div class="table-responsive">
                <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Date of Submission</th>
                            <th>Application Id</th>
                            <th>Category</th>
                            {{-- <th>Current Status</th> --}}
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allComplain as $index => $complain)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $complain->created_at->format('d-m-Y') }}</td>
                                <td>{{ $complain->application_id }}</td>
                                {{-- <td>{{ ucfirst($complain->complaint_type) }}</td> --}}
                                <td>
                                    @if (strtolower($complain->complaint_type) === 'excise')
                                        {{ ucfirst($complain->complaint_type) }}
                                    @else
                                        {{ strtoupper($complain->complaint_type) }}
                                    @endif
                                </td>

                                {{-- <td>{{ 'Pending' }}</td> --}}
                                <td>
                                    <a href="{{ route('user.setails', [$complain->secure_id]) }}"
                                        class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- DataTables CSS & JS -->


@endsection
