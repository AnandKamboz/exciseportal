@extends('hq.layouts.dashboard')

@section('title', 'HQ User List')
@section('page_title', 'Dashboard')

@section('content')

    <div class="card shadow-sm border-0 p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">HQ User List</h5>
            <a class="btn btn-primary text-white" href="{{ route('hq.user.create') }}">
                Add HQ User
            </a>
        </div>

        <div class="table-responsive">
            <table id="data-table" class="table table-bordered table-hover align-middle">
                <thead style="background:#FF8A73;color:#fff;">
                    <tr>
                        <th class="text-center">S.N.</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Mobile</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $index => $row)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>

                            <td class="text-center">
                                <i class="bi bi-person-badge-fill me-1 text-primary"></i>
                                {{ $row->name }}
                            </td>

                            <td class="text-center">
                                <i class="bi bi-telephone-fill me-1 text-success"></i>
                                {{ $row->mobile }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No HQ User Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection


{{-- ===============================
    CSS (Load once – preferably in layout)
================================ --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush


{{-- ===============================
    JS (Load once – preferably before </body>)
================================ --}}
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                ordering: true,
                searching: true,
                responsive: true
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
            });
        </script>
    @endif
@endpush
