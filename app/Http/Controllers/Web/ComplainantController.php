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

    //     // 🔹 Validation Rules for Each Complaint Type
    //     $rules = match ($type) {
    //         'gst' => [
    //             'gstFirmName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'gstGstin'       => 'nullable|alpha_num|size:15',
    //             'gstFirmAddress' => 'required|string',
    //             'gstLocality'    => 'required|string|max:255',
    //             'gstDistrict'    => 'required|string|max:255',
    //             'gstDescription' => 'required|string|max:2000',
    //             'gstProof'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         ],
    //         'vat' => [
    //             'vatFirmName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'vatTin'         => 'nullable|alpha_num',
    //             'vatFirmAddress' => 'required|string',
    //             'vatLocality'    => 'required|string|max:255',
    //             'vatDistrict'    => 'required|string|max:255',
    //             'vatDescription' => 'required|string|max:2000',
    //             'vatProof'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         ],
    //         'excise' => [
    //             'exciseName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'exciseDetails' => 'required|string|max:2000',
    //             'exciseDesc'    => 'required|string|max:255',
    //             'excisePlace'   => 'required|string|max:255',
    //             'exciseTime'    => 'required|string|max:255',
    //         ],
    //         default => [],
    //     };

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

    //     // 🔹 Generate Unique Application ID (if not set)
    //     if (empty($complaint->application_id)) {
    //         do {
    //             $randomDigits = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    //             $applicationId = strtoupper($type) . '-' . $randomDigits;
    //         } while (Complainant::where('application_id', $applicationId)->exists());

    //         $complaint->application_id = $applicationId;
    //     } else {
    //         $applicationId = $complaint->application_id;
    //     }

    //     $complaint->complaint_type = $type;

    //     // 🔹 GST Complaint Data
    //     if ($type === 'gst') {
    //         $complaint->gst_firm_name = $request->gstFirmName;
    //         $complaint->gst_gstin = strtoupper($request->gstGstin);
    //         $complaint->gst_firm_address = $request->gstFirmAddress;
    //         $complaint->gst_locality = $request->gstLocality;
    //         $complaint->gst_district = $request->gstDistrict;
    //         $complaint->gst_description = $request->gstDescription;

    //         if ($request->hasFile('gstProof')) {
    //             $file = $request->file('gstProof');
    //             $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    //             $file->storeAs("public/complaints/{$applicationId}/", $fileName);
    //             $complaint->gst_proof = $fileName;
    //         }
    //     }

    //     // 🔹 VAT Complaint Data
    //     if ($type === 'vat') {
    //         $complaint->vat_firm_name = $request->vatFirmName;
    //         $complaint->vat_tin = strtoupper($request->vatTin);
    //         $complaint->vat_firm_address = $request->vatFirmAddress;
    //         $complaint->vat_locality = $request->vatLocality;
    //         $complaint->vat_district = $request->vatDistrict;
    //         $complaint->vat_description = $request->vatDescription;

    //         if ($request->hasFile('vatProof')) {
    //             $file = $request->file('vatProof');
    //             $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    //             $file->storeAs("public/complaints/{$applicationId}/", $fileName);
    //             $complaint->vat_proof = $fileName;
    //         }
    //     }

    //     // 🔹 Excise Complaint Data
    //     if ($type === 'excise') {
    //         $complaint->excise_name = $request->exciseName;
    //         $complaint->excise_desc = $request->exciseDesc;
    //         $complaint->excise_place = $request->excisePlace;
    //         $complaint->excise_time = $request->exciseTime;
    //         $complaint->excise_details = $request->exciseDetails;
    //     }

    //     $complaint->is_completed = 1;
    //     $complaint->save();

    //     // 🔹 Update User Info if Missing
    //     $userMobile = Auth::user()->mobile;
    //     $user = User::where('mobile', $userMobile)->first();

    //     if ($user && (empty($user->aadhaar) || is_null($user->aadhaar))) {
    //         $user->update([
    //             'email'      => $request->informerEmail ?? null,
    //             'aadhaar'    => $request->informerAadhar,
    //             'address'    => $request->informerAddress,
    //             'district'   => 'Demo District',
    //             'updated_at' => now(),
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Complaint updated successfully. Your Application ID is: ' . $complaint->application_id,
    //         'application_id' => $complaint->application_id,
    //     ]);
    // }


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

    //     // 🔹 Validation Rules for Each Complaint Type
    //     $rules = match ($type) {
    //         'gst' => [
    //             'gstFirmName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'gstGstin'       => 'nullable|alpha_num|size:15',
    //             'gstFirmAddress' => 'required|string',
    //             'gstLocality'    => 'required|string|max:255',
    //             'gstDistrict'    => 'required|string|max:255',
    //             'gstDescription' => 'required|string|max:2000',
    //             'gstProof'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         ],
    //         'vat' => [
    //             'vatFirmName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'vatTin'         => 'nullable|alpha_num',
    //             'vatFirmAddress' => 'required|string',
    //             'vatLocality'    => 'required|string|max:255',
    //             'vatDistrict'    => 'required|string|max:255',
    //             'vatDescription' => 'required|string|max:2000',
    //             'vatProof'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //         ],
    //         'excise' => [
    //             'exciseName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'exciseDetails' => 'required|string|max:2000',
    //             'exciseDesc'    => 'required|string|max:255',
    //             'excisePlace'   => 'required|string|max:255',
    //             'exciseTime'    => 'required|string|max:255',
    //         ],
    //         default => [],
    //     };

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

    //     // 🔹 Generate Unique Application ID (if not set)
    //     if (empty($complaint->application_id)) {
    //         do {
    //             $randomDigits = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    //             $applicationId = strtoupper($type) . '-' . $randomDigits;
    //         } while (Complainant::where('application_id', $applicationId)->exists());

    //         $complaint->application_id = $applicationId;
    //     } else {
    //         $applicationId = $complaint->application_id;
    //     }

    //     $complaint->complaint_type = $type;

    //     // 🔹 GST Complaint Data
    //     if ($type === 'gst') {
    //         $complaint->gst_firm_name = $request->gstFirmName;
    //         $complaint->gst_gstin = strtoupper($request->gstGstin);
    //         $complaint->gst_firm_address = $request->gstFirmAddress;
    //         $complaint->gst_locality = $request->gstLocality;
    //         $complaint->gst_district = $request->gstDistrict;
    //         $complaint->gst_description = $request->gstDescription;

    //         if ($request->hasFile('gstProof')) {
    //             $file = $request->file('gstProof');
    //             $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    //             $file->storeAs("complaints/{$applicationId}", $fileName, 'public'); // ✅ Store in public
    //             $complaint->gst_proof = $fileName;
    //         }
    //     }

    //     // 🔹 VAT Complaint Data
    //     if ($type === 'vat') {
    //         $complaint->vat_firm_name = $request->vatFirmName;
    //         $complaint->vat_tin = strtoupper($request->vatTin);
    //         $complaint->vat_firm_address = $request->vatFirmAddress;
    //         $complaint->vat_locality = $request->vatLocality;
    //         $complaint->vat_district = $request->vatDistrict;
    //         $complaint->vat_description = $request->vatDescription;

    //         if ($request->hasFile('vatProof')) {
    //             $file = $request->file('vatProof');
    //             $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    //             $file->storeAs("complaints/{$applicationId}", $fileName, 'public'); // ✅ Store in public
    //             $complaint->vat_proof = $fileName;
    //         }
    //     }

    //     // 🔹 Excise Complaint Data
    //     if ($type === 'excise') {
    //         $complaint->excise_name = $request->exciseName;
    //         $complaint->excise_desc = $request->exciseDesc;
    //         $complaint->excise_place = $request->excisePlace;
    //         $complaint->excise_time = $request->exciseTime;
    //         $complaint->excise_details = $request->exciseDetails;
    //     }

    //     $complaint->is_completed = 1;
    //     $complaint->save();

    //     // 🔹 Update User Info if Missing
    //     $user = User::where('mobile', $mobile)->first();
    //     if ($user && (empty($user->aadhaar) || is_null($user->aadhaar))) {
    //         $user->update([
    //             'email'      => $request->informerEmail ?? null,
    //             'aadhaar'    => $request->informerAadhar,
    //             'address'    => $request->informerAddress,
    //             'district'   => 'Demo District',
    //             'updated_at' => now(),
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Complaint updated successfully. Your Application ID is: ' . $complaint->application_id,
    //         'application_id' => $complaint->application_id,
    //     ]);
    // }

    public function submitComplaint(Request $request)
{
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

    // 🔹 Validation Rules for Each Complaint Type
    $rules = match ($type) {
        'gst' => [
            'gstFirmName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
            'gstGstin'       => 'nullable|alpha_num|size:15',
            'gstFirmAddress' => 'required|string',
            'gstLocality'    => 'required|string|max:255',
            'gstDistrict'    => 'required|string|max:255',
            'gstDescription' => 'required|string|max:2000',
            'gstProof.*'     => 'file|mimes:pdf,jpg,jpeg,png|max:1024', // Each ≤ 1MB
        ],
        'vat' => [
            'vatFirmName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
            'vatTin'         => 'nullable|alpha_num',
            'vatFirmAddress' => 'required|string',
            'vatLocality'    => 'required|string|max:255',
            'vatDistrict'    => 'required|string|max:255',
            'vatDescription' => 'required|string|max:2000',
            'vatProof.*'     => 'file|mimes:pdf,jpg,jpeg,png|max:1024', // Each ≤ 1MB
        ],
        'excise' => [
            'exciseName'    => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
            'exciseDetails' => 'required|string|max:2000',
            'exciseDesc'    => 'required|string|max:255',
            'excisePlace'   => 'required|string|max:255',
            'exciseTime'    => 'required|string|max:255',
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

    // 🔹 Generate Unique Application ID (if not set)
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

    // 🔹 GST Complaint Data
    if ($type === 'gst') {
        $complaint->gst_firm_name = $request->gstFirmName;
        $complaint->gst_gstin = strtoupper($request->gstGstin);
        $complaint->gst_firm_address = $request->gstFirmAddress;
        $complaint->gst_locality = $request->gstLocality;
        $complaint->gst_district = $request->gstDistrict;
        $complaint->gst_description = $request->gstDescription;

        if ($request->hasFile('gstProof')) {
            $files = $request->file('gstProof');

            if (count($files) > 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can upload a maximum of 5 files.'
                ]);
            }

            $uploadedFiles = [];
            foreach ($files as $file) {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs("complaints/{$applicationId}", $fileName, 'public');
                $uploadedFiles[] = $fileName;
            }
            $complaint->gst_proof = json_encode($uploadedFiles); // store as JSON
        }
    }

    // 🔹 VAT Complaint Data
    if ($type === 'vat') {
        $complaint->vat_firm_name = $request->vatFirmName;
        $complaint->vat_tin = strtoupper($request->vatTin);
        $complaint->vat_firm_address = $request->vatFirmAddress;
        $complaint->vat_locality = $request->vatLocality;
        $complaint->vat_district = $request->vatDistrict;
        $complaint->vat_description = $request->vatDescription;

        if ($request->hasFile('vatProof')) {
            $files = $request->file('vatProof');

            if (count($files) > 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can upload a maximum of 5 files.'
                ]);
            }

            $uploadedFiles = [];
            foreach ($files as $file) {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs("complaints/{$applicationId}", $fileName, 'public');
                $uploadedFiles[] = $fileName;
            }
            $complaint->vat_proof = json_encode($uploadedFiles); // store as JSON
        }
    }

    // 🔹 Excise Complaint Data
    if ($type === 'excise') {
        $complaint->excise_name = $request->exciseName;
        $complaint->excise_desc = $request->exciseDesc;
        $complaint->excise_place = $request->excisePlace;
        $complaint->excise_time = $request->exciseTime;
        $complaint->excise_details = $request->exciseDetails;
    }

    $complaint->is_completed = 1;
    $complaint->save();

    // 🔹 Update User Info if Missing
    $user = User::where('mobile', $mobile)->first();
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

