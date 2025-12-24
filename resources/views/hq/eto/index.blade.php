@extends('hq.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
    <div class="card shadow-sm border-0 p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">ETO List</h5>
            <span class="badge bg-primary">
                <a class="btn btn-primary text-white" href="{{ route('hq.eto.create') }}">Add ETO</a>
            </span>
        </div>

        <div class="table-responsive">
            <table id="complaintsTable" class="table table-bordered table-hover align-middle">
                <thead style="background: #FF8A73; color: #fff;">
                    <tr>
                        {{-- <th style="width: 60px;">#</th> --}}
                        <th class="text-center">ETO Name</th>
                        <th class="text-center">ETO Mobile</th>
                        <th class="text-center">District</th>
                        <th class="text-center">Ward No.</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $index => $row)
                        <tr>
                            {{-- <td class="text-center fw-semibold">
                                {{ $index + 1 }}
                            </td> --}}

                            {{-- ETO Name --}}
                            <td class="text-center">
                                <i class="bi bi-person-badge-fill me-1 text-primary"></i>
                                {{ $row->name }}
                            </td>

                            {{-- Mobile --}}
                            <td class="text-center">
                                <i class="bi bi-telephone-fill me-1 text-success"></i>
                                {{ $row->mobile }}
                            </td>

                            {{-- District --}}
                            <td class="text-center">
                                <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                                {{ $row->district_name }}
                            </td>

                            {{-- Ward No --}}
                            <td class="text-center">
                                <i class="bi bi-building-fill me-1 text-warning"></i>
                                Ward No {{ $row->ward_no }}
                            </td>
                        </tr>
                    @endforeach

                    @if ($users->count() === 0)
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No ETO found
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.js"
        integrity="sha512-pnPZhx5S+z5FSVwy62gcyG2Mun8h6R+PG01MidzU+NGF06/ytcm2r6+AaWMBXAnDHsdHWtsxS0dH8FBKA84FlQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
