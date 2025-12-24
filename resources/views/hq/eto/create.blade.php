@extends('hq.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')

    <div class="card shadow-sm border-0 p-4">
        <h5 class="fw-bold mb-4">Assign District & Ward</h5>

        {{-- <form method="POST" action="{{ route('hq.eto.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">ETO Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter ETO Name" required>
            </div>

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

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Ward Number
                </label>
                <input type="number" name="ward_no" class="form-control" placeholder="Enter Ward Number" required>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>

        </form> --}}

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

@endsection
