@extends('hq.layouts.dashboard')

@section('title', 'DETC Transfer')

@section('content')
    <div class="card shadow-sm border-0 p-4">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-arrow-left-right"></i> DETC Transfer
        </h5>

        <form action="{{ route('hq.detc.transfer.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- FROM DETC --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        DETC To Be Transferred (FROM)
                    </label>

                    <select name="from_detc" class="form-select @error('from_detc') is-invalid @enderror" required>
                        <option value="">Select DETC</option>

                        @foreach ($detcs as $detc)
                            <option value="{{ $detc->secure_id }}" data-district-id="{{ $detc->district_id }}">
                                {{ $detc->detc_label }}
                            </option>
                        @endforeach
                    </select>

                    @error('from_detc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TO DETC --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        Transfer With (TO)
                    </label>

                    <select name="to_detc" class="form-select @error('to_detc') is-invalid @enderror" required>
                        <option value="">Select DETC</option>

                        @foreach ($detcs as $detc)
                            <option value="{{ $detc->secure_id }}" data-district-id="{{ $detc->district_id }}">
                                {{ $detc->detc_label }}
                            </option>
                        @endforeach
                    </select>

                    @error('to_detc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- REMARKS (FULL WIDTH) --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Transfer Remarks</label>
                <textarea name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror"
                    placeholder="Reason for DETC transfer">{{ old('remarks') }}</textarea>

                @error('remarks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="text-end">
                <a href="{{ route('hq.detc.list') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-left-right"></i> Transfer DETC
                </button>
            </div>
        </form>

    </div>

    {{-- OPTIONAL: prevent same DETC selection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fromSelect = document.querySelector('select[name="from_detc"]');
            const toSelect = document.querySelector('select[name="to_detc"]');

            function disableSameOption() {
                const fromValue = fromSelect.value;

                Array.from(toSelect.options).forEach(option => {
                    option.disabled = (option.value === fromValue && fromValue !== '');
                });
            }

            fromSelect.addEventListener('change', disableSameOption);
        });
    </script>
@endsection
