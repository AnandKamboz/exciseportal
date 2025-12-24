@extends('hq.layouts.dashboard')

@section('title', 'Complaint Details')
@section('page_title', 'Dashboard')

@section('content')
    <div class="card p-3">
        <h5 class="fw-bold mb-3">Information Details</h5>

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
                    @foreach ($complaints as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ optional($row->created_at)->format('d-m-Y') }}
                            </td>
                            <td>
                                <strong>{{ $row->application_id }}</strong>
                            </td>
                            <td>
                                {{ ucwords(str_replace('_', ' ', $row->type_of_complaint)) }}
                            </td>
                            <td>
                                <a href="{{ route('hq.details', ['secure_id' => $row->secure_id]) }}"
                                    class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
