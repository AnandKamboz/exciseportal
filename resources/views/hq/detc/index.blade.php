@extends('hq.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
    <div class="card shadow-sm border-0 p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">DETC List</h5>
            {{-- <span class="badge bg-primary">
                <a class="btn btn-primary text-white" href="{{ route('hq.eto.create') }}">Add DETC</a>
            </span> --}}
        </div>

        <div class="table-responsive">
            <table id="complaintsTable" class="table table-bordered table-hover align-middle">
                <thead style="background: #FF8A73; color: #fff;">
                    <tr>
                        <th class="text-center">S.N.</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Mobile</th>
                        <th class="text-center">District</th>
                        <th class="text-center d-none">Ward No.</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $key => $row)
                        <tr>
                            <td class="text-center">
                                <i class="bi bi-list-ol me-1 text-primary"></i>
                                {{ $key + 1 }}
                            </td>

                            {{-- NAME --}}
                            <td class="text-center">
                                <i class="bi bi-person-badge-fill me-1 text-primary"></i>
                                {{ $row->name }}
                            </td>

                            {{-- MOBILE --}}
                            <td class="text-center">
                                <i class="bi bi-telephone-fill me-1 text-success"></i>
                                {{ $row->mobile }}
                            </td>

                            {{-- DISTRICT --}}
                            <td class="text-center">
                                <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                                {{ $row->district_name }}
                            </td>

                            {{-- WARD (HIDDEN) --}}
                            <td class="text-center d-none">
                                Ward No {{ $row->ward_no }}
                            </td>

                            {{-- STATUS TOGGLE --}}
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input status-toggle" type="checkbox"
                                        data-id="{{ $row->secure_id }}" {{ $row->is_active ? 'checked' : '' }}>
                                </div>
                            </td>

                            {{-- ACTION --}}
                            <td class="text-center">
                                <a href="{{ route('hq.detc.edit', $row->secure_id) }}" class="btn btn-sm btn-warning"
                                    title="Edit DETC">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>

                        </tr>
                    @endforeach
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
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This DETC will be permanently deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
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
    <script>
        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {

                let checkbox = this;
                let secureId = checkbox.dataset.id;
                let status = checkbox.checked ? 1 : 0;

                Swal.fire({
                    title: status ? 'Activate DETC?' : 'Deactivate DETC?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('hq.detc.toggle') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    secure_id: secureId,
                                    status: status
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire('Success', data.message, 'success');
                            })
                            .catch(() => {
                                checkbox.checked = !status;
                                Swal.fire('Error', 'Something went wrong', 'error');
                            });

                    } else {
                        // ❌ revert toggle
                        checkbox.checked = !status;
                    }
                });
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
