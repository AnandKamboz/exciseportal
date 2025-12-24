@extends('hq.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')

    <div class="card shadow-sm border-0 p-4">
        <h5 class="fw-bold mb-4">Assign District & Ward</h5>
        <form method="POST" action="{{ route('hq.eto.store') }}">
            @csrf

            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ETO Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter ETO Name" required>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            District
                        </label>
                        <select name="district_id" class="form-select" required>
                            <option value="">Select District</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Ward Number
                        </label>
                        <input type="number" name="ward_no" class="form-control" placeholder="Enter Ward Number" required>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mobile</label>
                        <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile" maxlength="10"
                            pattern="[0-9]{10}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            required>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>

        </form>

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
