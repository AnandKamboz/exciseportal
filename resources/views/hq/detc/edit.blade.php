@extends('hq.layouts.dashboard')

@section('title', 'Edit DETC')

@section('content')
    <div class="card shadow-sm p-4">
        <h5 class="fw-bold mb-3">Edit DETC</h5>

        <form action="{{ route('hq.detc.update', $detc->secure_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $detc->name) }}" maxlength="50" pattern="[A-Za-z ]+"
                            oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '');" required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mobile Number</label>

                        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                            value="{{ old('mobile', $detc->mobile) }}" maxlength="10" pattern="[0-9]{10}"
                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
                            required>

                        @error('mobile')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">District</label>
                        <input type="text" class="form-control" value="{{ $districtName ?? 'N/A' }}" disabled>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('hq.detc.list') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Update
                </button>
            </div>
        </form>
    </div>
@endsection
