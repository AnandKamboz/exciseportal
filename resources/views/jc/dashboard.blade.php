@extends('jc.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="card p-3">
            <h5 class="fw-bold  mb-3">Complaint Details</h5>
            <div class="table-responsive">
                <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Date of Receiving</th>
                            <th>Application Id</th>
                            {{-- <th>Category</th> --}}
                            <th>Type of Information</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($informations as $index => $information)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $information->created_at->format('d-m-Y') }}</td>
                                <td>{{ $information->application_id }}</td>
                                {{-- <td>{{ ucfirst($information->complaint_type) }}</td> --}}
                                <td>{{ ucwords(str_replace('_', ' ', $information->type_of_complaint)) }}</td>
                                <td>
                                    <a href="{{ route('jc.details', [$information->secure_id]) }}"
                                        class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Swal JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK', 
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        </script>
    @endif


@endsection
