@extends('hq.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">

            {{-- 🟣 Total Information --}}
            <div class="col-md-3 mb-3">
                <div class="card text-center p-3">
                    <h5>Total Information</h5>
                    <h2>{{ $allComplain->count() }}</h2>
                    <a href="{{ url('hq/complaints/all') }}" class="new-butt">
                        View All
                    </a>
                </div>
            </div>


            {{-- 🟢 Open / New Complaints --}}
            <div class="col-md-3 mb-3">
                <div class="card text-center p-3">
                    <h5>Open / New Information</h5>
                    <h2>{{ $openNew }}</h2>
                    <a href="{{ url('hq/complaints/open') }}" class="new-butt">View All</a>
                </div>
            </div>

            {{-- 🟡 Pending with Applicant --}}
            <div class="col-md-3 mb-3">
                <div class="card text-center p-3">
                    <h5>Pending with Applicant</h5>
                    <h2>{{ $pendingFromApplicant }}</h2>
                    <a href="{{ url('hq/complaints/pending-applicant') }}" class="new-butt">
                        View All
                    </a>
                </div>
            </div>


            {{-- 🔵 Under Review (DETC / ETO / HQ) --}}
            <div class="col-md-3 mb-3">
                <div class="card text-center p-3">
                    <h5>Under Review</h5>
                    <h2>{{ $underReview }}</h2>

                    <a href="{{ url('hq/complaints/under-review') }}" class="new-butt">
                        View All
                    </a>
                </div>
            </div>

            {{-- hello --}}
            {{-- 🔴 Closed Complaints --}}
            <div class="col-md-3 mb-3">
                <div class="card text-center p-3">
                    <h5>Closed Information</h5>
                    <h2>{{ $closed }}</h2>
                    <a href="{{ url('hq/complaints/closed') }}" class="new-butt">
                        View All
                    </a>
                </div>
            </div>

            {{-- 🕵️ With DETC --}}
            <div class="col-md-3 mb-3">
                <div class="card text-center p-3">
                    <h5>With DETC</h5>
                    <h2>{{ $allComplain->where('current_owner', 'DETC')->count() }}</h2>
                    <a href="{{ url('hq/complaints/with-detc') }}" class="new-butt">
                        View All
                    </a>
                </div>
            </div>

            {{-- 🕵️ With ETO --}}
            <div class="col-md-3 mb-3">
                <div class="card text-center p-3">
                    <h5>With ETO</h5>
                    <h2>{{ $allComplain->where('current_owner', 'ETO')->count() }}</h2>
                    <a href="{{ url('hq/complaints/with-eto') }}" class="new-butt">
                        View All
                    </a>
                </div>
            </div>

            {{-- 📄 With HQ --}}
            <div class="col-md-3 mb-3">
                <div class="card text-center p-3">
                    <h5>With HQ</h5>
                    <h2>{{ $allComplain->where('current_owner', 'HQ')->count() }}</h2>
                    <a href="{{ url('hq/complaints/with-hq') }}" class="new-butt">
                        View All
                    </a>
                </div>
            </div>
        </div>


        <div class="card p-3">
            <h5 class="fw-bold  mb-3">Information Details</h5>

            <div class="table-responsive">
                <table id="complaintsTable" class="table table-hover table-striped">
                    <thead style="background: #FF8A73; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Date of Receiving</th>
                            <th>Application Id</th>
                            <th>Type of Information</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($informations as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->created_at->format('d-m-Y') }}</td>
                                <td>{{ $row->application_id }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $row->type_of_complaint)) }}</td>
                                <td>
                                    <a href="{{ route('hq.details', [$row->secure_id]) }}"
                                        class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
