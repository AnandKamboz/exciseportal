<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Complainant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;



class ComplainantController extends Controller
{
    public function complainant(Request $request)
    {
        if (!Auth::check() || !$request->session()->has('mobile')) {
            return redirect('/')->with('error', 'Unauthorized access!');
        }

        $districts = DB::table('districts')->get();
        $userMobile = Auth::user()->mobile;
        $userDataForNewApplication = Complainant::where('complainant_phone', $userMobile)->where('is_completed', 0)->first();

        $userData = Complainant::where('complainant_phone', $userMobile)->first();
        return view('complainant.create',compact('userMobile','userData','districts','userDataForNewApplication'));
    }

    public function saveInformer(Request $request)
    {
        $request->validate([
            'informer_name' => 'required|string|max:100',
            'informer_aadhar' => 'required|digits:12',
            'informer_address' => 'required|string',
            'informer_email' => 'nullable|email',
        ]);

        $mobile = auth()->user()->mobile;

        $existingComplaint = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 0)
            ->first();

        if ($existingComplaint) {
            $existingComplaint->update([
                'complainant_name' => $request->informer_name,
                'complainant_aadhar' => $request->informer_aadhar,
                'complainant_address' => $request->informer_address,
                'complainant_email' => $request->informer_email ?? null,
            ]);

            $message = 'Existing complaint updated successfully!';
        } else {
            $complaint = new Complainant();
            $complaint->secure_id = Str::random(32);
            $complaint->complainant_name = $request->informer_name;
            $complaint->complainant_aadhar = $request->informer_aadhar;
            $complaint->complainant_address = $request->informer_address;
            $complaint->complainant_email = $request->informer_email ?? null;
            $complaint->complainant_phone = $mobile;
            $complaint->user_id = auth()->id();
            $complaint->is_completed = 0;
            $complaint->save();

            $message = 'New informer record created successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }


    public function saveComplaintType(Request $request)
    {
        $request->validate([
            'complaint_type' => 'required|in:gst,excise,vat',
        ]);

        $user = Auth::user();
        $mobile = $user->mobile;

        $complaint = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 0)
            ->first();

        if ($complaint) {
            $complaint->complaint_type = $request->complaint_type;
            $complaint->save();
            $message = 'Complaint type updated successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }


    // public function submitComplaint(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'taxType' => 'required|in:gst,vat,excise',
    //     ], [
    //         'taxType.required' => 'Complaint type is required.',
    //         'taxType.in' => 'Invalid complaint type selected.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ]);
    //     }

    //     $type = strtolower($request->taxType);
    //     $mobile = Auth::user()->mobile;

    //     $rules = [];
    //     if ($type === 'gst') {
    //         $rules = [
    //             'gstFirmName'   => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'gstGstin'      => 'required|alpha_num|size:15',
    //             'gstFirmAddress'=> 'required|string',
    //             'gstProof'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
    //         ];
    //     } elseif ($type === 'vat') {
    //         $rules = [
    //             'vatFirmName'   => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'vatTin'        => 'required|alpha_num',
    //             'vatFirmAddress'=> 'required|string',
    //             'vatProof'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
    //         ];
    //     } elseif ($type === 'excise') {
    //         $rules = [
    //             'exciseName' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'exciseDetails' => 'required|string|max:2000',
    //             'exciseDesc' => 'nullable|string|max:255',
    //             'excisePlace' => 'nullable|string|max:255',
    //             'exciseTime' => 'nullable|string|max:255',
    //         ];
    //     }

    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ]);
    //     }

        
    //     $complaint = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 0)
    //         ->first();

    //     if (!$complaint) {
         
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No pending complaint found for update.',
    //         ]);
    //     }

    //     if (empty($complaint->application_id)) {
    //         do {
    //             $randomDigits = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    //             $applicationId = strtoupper($type) . '-' . $randomDigits;
    //         } while (Complainant::where('application_id', $applicationId)->exists());

    //         $complaint->application_id = $applicationId;
    //     }

       
    //     $complaint->complaint_type = $type;

       
    //     if ($type === 'gst') {
    //         $complaint->gst_firm_name = $request->gstFirmName;
    //         $complaint->gst_gstin = strtoupper($request->gstGstin);
    //         $complaint->gst_firm_address = $request->gstFirmAddress;

    //         if ($request->hasFile('gstProof')) {
    //             $file = $request->file('gstProof');
    //             $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    //             $file->storeAs('public/complaints/gst/', $fileName);
    //             $complaint->gst_proof = $fileName;
    //         }
    //     }

    //     if ($type === 'vat') {
    //         $complaint->vat_firm_name = $request->vatFirmName;
    //         $complaint->vat_tin = strtoupper($request->vatTin);
    //         $complaint->vat_firm_address = $request->vatFirmAddress;

    //         if ($request->hasFile('vatProof')) {
    //             $file = $request->file('vatProof');
    //             $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    //             $file->storeAs('public/complaints/vat/', $fileName);
    //             $complaint->vat_proof = $fileName;
    //         }
    //     }

    //     if ($type === 'excise') {
    //         $complaint->excise_name = $request->exciseName;
    //         $complaint->excise_desc = $request->exciseDesc;
    //         $complaint->excise_place = $request->excisePlace;
    //         $complaint->excise_time = $request->exciseTime;
    //         $complaint->excise_details = $request->exciseDetails;
    //     }

    //     $complaint->is_completed = 1;
    //     $complaint->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Complaint updated successfully.',
    //         'application_id' => $complaint->application_id,
    //     ]);
    // }

    public function submitComplaint(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'taxType' => 'required|in:gst,vat,excise',
        ], [
            'taxType.required' => 'Complaint type is required.',
            'taxType.in' => 'Invalid complaint type selected.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $type = strtolower($request->taxType);
        $mobile = Auth::user()->mobile;

        $rules = match ($type) {
            'gst' => [
                'gstFirmName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
                'gstGstin'       => 'required|alpha_num|size:15',
                'gstFirmAddress' => 'required|string',
                'gstProof'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ],
            'vat' => [
                'vatFirmName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
                'vatTin'         => 'required|alpha_num',
                'vatFirmAddress' => 'required|string',
                'vatProof'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ],
            'excise' => [
                'exciseName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
                'exciseDetails' => 'required|string|max:2000',
                'exciseDesc'    => 'nullable|string|max:255',
                'excisePlace'   => 'nullable|string|max:255',
                'exciseTime'    => 'nullable|string|max:255',
            ],
            default => [],
        };

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $complaint = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 0)
            ->first();

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'No pending complaint found for update.',
            ]);
        }

        if (empty($complaint->application_id)) {
            do {
                $randomDigits = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                $applicationId = strtoupper($type) . '-' . $randomDigits;
            } while (Complainant::where('application_id', $applicationId)->exists());

            $complaint->application_id = $applicationId;
        } else {
            $applicationId = $complaint->application_id;
        }

        $complaint->complaint_type = $type;

        if ($type === 'gst') {
            $complaint->gst_firm_name = $request->gstFirmName;
            $complaint->gst_gstin = strtoupper($request->gstGstin);
            $complaint->gst_firm_address = $request->gstFirmAddress;

            if ($request->hasFile('gstProof')) {
                $file = $request->file('gstProof');
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

                $file->storeAs("public/complaints/{$applicationId}/", $fileName);

                $complaint->gst_proof = $fileName;
            }
        }

        // ✅ VAT Complaint Data
        if ($type === 'vat') {
            $complaint->vat_firm_name = $request->vatFirmName;
            $complaint->vat_tin = strtoupper($request->vatTin);
            $complaint->vat_firm_address = $request->vatFirmAddress;

            if ($request->hasFile('vatProof')) {
                $file = $request->file('vatProof');
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

                $file->storeAs("public/complaints/{$applicationId}/", $fileName);

                $complaint->vat_proof = $fileName;
            }
        }

        if ($type === 'excise') {
            $complaint->excise_name = $request->exciseName;
            $complaint->excise_desc = $request->exciseDesc;
            $complaint->excise_place = $request->excisePlace;
            $complaint->excise_time = $request->exciseTime;
            $complaint->excise_details = $request->exciseDetails;
        }

        $complaint->is_completed = 1;
        $complaint->save();


        // For creating user record if not exists
        // $userMobile = Auth::user()->mobile;
        // $userExists = User::where('mobile', $userMobile)->exists();

        // if (!$userExists) {
        //     User::create([
        //         'email'      => $request->informerEmail ?? null,
        //         'mobile'     => $userMobile,
        //         'aadhaar'    => $request->informerAadhar,
        //         'address'    => $request->informerAddress,
        //         'district'   => 'Demo District',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        $userMobile = Auth::user()->mobile;
        $user = User::where('mobile', $userMobile)->first();

        if ($user && (empty($user->aadhaar) || is_null($user->aadhaar))) {
            $user->update([
                'email'      => $request->informerEmail ?? null,
                'aadhaar'    => $request->informerAadhar,
                'address'    => $request->informerAddress,
                'district'   => 'Demo District',
                'updated_at' => now(),
            ]);
        }






        return response()->json([
            'success' => true,
            'message' => 'Complaint updated successfully. Your Application ID is: ' . $complaint->application_id,
            'application_id' => $complaint->application_id,
        ]);
    }



}

