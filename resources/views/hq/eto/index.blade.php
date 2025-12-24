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
                        <th class="text-center">ETO Name</th>
                        <th class="text-center">ETO Mobile</th>
                        <th class="text-center">District</th>
                        <th class="text-center">Ward No.</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $index => $row)
                        <tr>
                            <td class="text-center">
                                <i class="bi bi-person-badge-fill me-1 text-primary"></i>
                                {{ $row->name }}
                            </td>

                            <td class="text-center">
                                <i class="bi bi-telephone-fill me-1 text-success"></i>
                                {{ $row->mobile }}
                            </td>

                            <td class="text-center">
                                <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                                {{ $row->district_name }}
                            </td>

                            <td class="text-center">
                                <i class="bi bi-building-fill me-1 text-warning"></i>
                                Ward No {{ $row->ward_no }}
                            </td>

                            <td class="text-center">
                                <form action="{{ route('hq.eto.delete', $row->secure_id) }}" method="POST"
                                    class="delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
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



    <script>
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // stop normal submit

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This ETO will be permanently deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // now submit form
                    }
                });
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
