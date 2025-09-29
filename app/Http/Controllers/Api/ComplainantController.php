<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Complainant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;


class ComplainantController extends Controller
 {
    // public function storeFirstStep( Request $request )
    // {
    //     $data = $request->validate( [
    //         'complainant_name' => 'required|string|max:255',
    //         'complaint_type'   => 'required',
    //         'mobile' => 'required|numeric|digits:10',
    //         'email' => 'required|email|unique:users,email',
    //         'aadhaar' => 'required|digits:12',
    //         'address'          => 'required|string',
    //         'upload_document'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //     ] );

    //     $data[ 'mobile' ] = Auth::user()->mobile;

    //     $data[ 'is_fraud_related' ] = 0;

    //     $complaint = Complainant::where( 'mobile', Auth::user()->mobile )
    //     ->where( 'is_completed', false )
    //     ->first();

    //     $complaint = Complainant::where( 'mobile', Auth::user()->mobile )->first();

    //     if ( $complaint && $complaint->is_completed == 1 ) {
    //         return response()->json( [
    //             'message' => 'Complaint already submitted for this mobile number.'
    //         ], 403 );
    //     }

    //     if ( $complaint ) {
    //         $complaint->update( $data );
    //     } else {
    //         do {
    //             $complaintId = strtoupper( 'CMP-' . rand( 100000, 999999 ) );
    //         }
    //         while ( Complainant::where( 'complaint_id', $complaintId )->exists() );

    //         do {
    //             $secureId = bin2hex( random_bytes( 16 ) );
    //         }
    //         while ( Complainant::where( 'secure_id', $secureId )->exists() );

    //         $data[ 'complaint_id' ] = $complaintId;
    //         $data[ 'secure_id' ]    = $secureId;
    //         $data[ 'is_completed' ] = false;

    //         $complaint = Complainant::create( $data );
    //     }

    //     if ( $request->hasFile( 'upload_document' ) ) {
    //         $file = $request->file( 'upload_document' );
    //         $fileName = $file->getClientOriginalName();
    //         $path = $file->storeAs(
    //             'complaints/' . $complaint->complaint_id,
    //             $fileName,
    //             'public'
    //         );

    //         $complaint->upload_document = $path;
    //         $complaint->save();
    //     }

    //     User::where( 'mobile', Auth::user()->mobile )
    //     ->update( [
    //         'name'    => $data[ 'complainant_name' ],
    //         'email'   => $data[ 'email' ],
    //         'aadhaar' => $data[ 'aadhaar' ],
    //         'address' => $data[ 'address' ],
    //         'updated_at' => now(),
    //     ] );

    //     return response()->json( [
    //         'message'   => 'Step 1 saved',
    //         'complaint' => $complaint
    //     ] );
    // }

    public function storeFirstStep(Request $request)
    {
            $data = $request->validate([
                'complainant_name' => 'required|string|max:255',
                'complaint_type'   => 'required',
                'mobile'           => 'required|numeric|digits:10',
                'email'            => 'required',
                'aadhaar'          => 'required|digits:12',
                'address'          => 'required|string',
                'upload_document'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            // dd('sdsdsd');

            $data['mobile'] = Auth::user()->mobile;
            $data['is_fraud_related'] = 0;

            $complaint = Complainant::where('mobile', Auth::user()->mobile)->first();

            if ($complaint && $complaint->is_completed == 0) {
                $complaint->update($data);
            } else {
                do {
                    $complaintId = strtoupper('CMP-' . rand(100000, 999999));
                } while (Complainant::where('complaint_id', $complaintId)->exists());

                do {
                    $secureId = bin2hex(random_bytes(16));
                } while (Complainant::where('secure_id', $secureId)->exists());

                $data['complaint_id'] = $complaintId;
                $data['secure_id']    = $secureId;
                $data['is_completed'] = false;

                $complaint = Complainant::create($data);
            }

            if ($request->hasFile('upload_document')) {
                $file = $request->file('upload_document');
                $fileName = $file->getClientOriginalName();
                $path = $file->storeAs(
                    'complaints/' . $complaint->complaint_id,
                    $fileName,
                    'public'
                );

                $complaint->upload_document = $path;
                $complaint->save();
            }

            User::where('mobile', Auth::user()->mobile)
                ->update([
                    'name'       => $data['complainant_name'],
                    'email'      => $data['email'],
                    'aadhaar'    => $data['aadhaar'],
                    'address'    => $data['address'],
                    'updated_at' => now(),
                ]);

            // return response()->json([
            //     'message'   => 'Step 1 saved',
            //     'complaint' => $complaint
            // ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Step 1 saved',
                'complaint' => $complaint
            ], 200);

    }


    public function storeSecondStep( Request $request )
    {

        $data = $request->validate( [
            'secure_id' => 'required',
            'is_fraud_related' => 'required|in:0,1',
        ] );

        $complaint = Complainant::where( 'secure_id', $data[ 'secure_id' ] )->where('is_completed',0)->first();

        // if ( $complaint->is_completed == '1' ) {
        //     return response()->json( [ 'message' => 'Complaint already submitted.' ], 403 );
        // }

        if ($complaint->is_completed == '1') {
            return response()->json([
                'success' => false,
                'message' => 'Complaint already submitted.'
            ], 403);
        }


        // if ( !$complaint ) {
        //     return response()->json( [
        //         'message' => 'Complaint not found.',
        //     ], 404 );
        // }

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found.',
            ], 404);
        }


        $complaint->update( [
            'is_fraud_related' => $data[ 'is_fraud_related' ],
        ] );

        return response()->json( [
            'success'   => true,
            'message'   => 'Step 2 saved',
            'complaint' => $complaint,
        ] );
    }

    public function store( Request $request )
    {
        $data = $request->validate( [
            'secure_id'       => 'required|string|exists:complainants,secure_id',
            'firm_name'       => 'required|string|max:255',
            'gstin'           => 'required|string|max:15',
            'firm_address'    => 'required|string|max:500',
            'proof_document'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'remarks'         => 'required|string|max:1000',
            'district_id' => 'required|integer',
        ] );

        $complaint = Complainant::where( 'secure_id', $data[ 'secure_id' ] )->where('is_completed',0)->first();

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found.',
            ], 404);
        }

        if ( $request->hasFile( 'proof_document' ) ) {
            if ( $complaint->proof_document && Storage::disk( 'public' )->exists( $complaint->proof_document ) ) {
                Storage::disk( 'public' )->delete( $complaint->proof_document );
            }

            $file = $request->file( 'proof_document' );
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs(
                'complaints/' . $complaint->complaint_id,
                $fileName,
                'public'
            );

            $data[ 'proof_document' ] = $path;
        } else {
            unset( $data[ 'proof_document' ] );
        }
        

        $data[ 'is_completed' ] = 1;
        $data['district_id'] = $request->district_id;
        $complaint->update( $data );

        $user = Auth::user();
        if ($user) {
            $user->district_id = $request->district_id;
            $user->save();
        }

        return response()->json( [
            'success'   => true,
            'message'   => 'Step 3 saved. Complaint submitted successfully.',
            'complaint' => $complaint,
        ] );
    }
}
