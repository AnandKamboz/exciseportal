<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\IndiaDistrict;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ComplainantController extends Controller
{
    public function complainant(Request $request)
    {
        if (! Auth::check() || ! $request->session()->has('mobile')) {
            return redirect('/')->with('error', 'Unauthorized access!');
        }

        $districts = DB::table('districts')->get();
        $userMobile = Auth::user()->mobile;
        $userDataForNewApplication = Complainant::where('complainant_phone', $userMobile)->where('is_completed', 1)->first();
        $userData = Complainant::where('complainant_phone', $userMobile)->where('is_completed', 0)->first();
        $districts = DB::table('districts')
            ->orderBy('name', 'asc')
            ->get();

        $indiaStates = State::all();

        return view('complainant.create', compact('indiaStates', 'userMobile', 'userData', 'districts', 'userDataForNewApplication', 'districts'));
    }

    // public function saveInformer(Request $request)
    // {
    //     $request->validate([
    //         'informer_name' => 'required|string|max:100',
    //         'informer_aadhar' => 'required|digits:12',
    //         'informer_address' => 'required|string',
    //         'informer_email' => 'nullable|email',
    //         'informer_city' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:50'],
    //         'informer_district' => ['required'],
    //         'my_state' => 'required',
    //     ]);

    //     $mobile = auth()->user()->mobile;

    //     $existingComplaint = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 0)
    //         ->first();

    //     if ($existingComplaint) {
    //         $existingComplaint->update([
    //             'complainant_name' => $request->informer_name,
    //             'complainant_aadhar' => $request->informer_aadhar,
    //             'complainant_address' => $request->informer_address,
    //             'complainant_city' => $request->informer_city,
    //             'complainant_district' => $request->informer_district,
    //             'complainant_state' => $request->my_state,
    //             'complainant_email' => $request->informer_email ?? null,
    //         ]);

    //         $message = 'Existing complaint updated successfully!';
    //     } else {
    //         $complaint = new Complainant();
    //         $complaint->secure_id = Str::random(32);
    //         $complaint->complainant_name = $request->informer_name;
    //         $complaint->complainant_aadhar = $request->informer_aadhar;
    //         $complaint->complainant_address = $request->informer_address;
    //         $complaint->complainant_city = $request->informer_city;
    //         $complaint->complainant_district = $request->informer_district;
    //         $complaint->complainant_email = $request->informer_email ?? null;
    //         $complaint->complainant_state = $request->my_state ?? null;
    //         $complaint->complainant_phone = $mobile;
    //         $complaint->user_id = auth()->id();
    //         $complaint->is_completed = 0;
    //         $complaint->save();

    //         $message = 'New informer record created successfully!';
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => $message,
    //     ]);
    // }

    public function saveInformer(Request $request)
    {
        $request->validate([
            'informer_name' => 'required|string|max:100',
            'informer_aadhar' => 'required|digits:12',
            'informer_address' => 'required|string',
            'informer_email' => 'nullable|email',
            'informer_city' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:50'],
            'informer_district' => ['required'],
            'my_state' => 'required',
        ]);

        $mobile = auth()->user()->mobile;

        // 1️⃣ CHECK — If a completed record already exists
        $completedRecord = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 1)
            ->first();

        if ($completedRecord) {

            // 2️⃣ CHECK — If incomplete record exists, update it with completed record values
            $existingIncomplete = Complainant::where('complainant_phone', $mobile)
                ->where('is_completed', 0)
                ->first();

            if ($existingIncomplete) {
                $existingIncomplete->update([
                    'complainant_name' => $completedRecord->complainant_name,
                    'complainant_aadhar' => $completedRecord->complainant_aadhar,
                    'complainant_address' => $completedRecord->complainant_address,
                    'complainant_city' => $completedRecord->complainant_city,
                    'complainant_district' => $completedRecord->complainant_district,
                    'complainant_state' => $completedRecord->complainant_state,
                    'complainant_email' => $completedRecord->complainant_email,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data auto-filled from previous completed complaint!',
                ]);
            }

            // 3️⃣ If no incomplete exists → create new using completed record values
            $complaint = new Complainant;
            $complaint->secure_id = Str::random(32);
            $complaint->complainant_name = $completedRecord->complainant_name;
            $complaint->complainant_aadhar = $completedRecord->complainant_aadhar;
            $complaint->complainant_address = $completedRecord->complainant_address;
            $complaint->complainant_city = $completedRecord->complainant_city;
            $complaint->complainant_district = $completedRecord->complainant_district;
            $complaint->complainant_email = $completedRecord->complainant_email;
            $complaint->complainant_state = $completedRecord->complainant_state;
            $complaint->complainant_phone = $mobile;
            $complaint->user_id = auth()->id();
            $complaint->is_completed = 0;
            $complaint->save();

            return response()->json([
                'success' => true,
                'message' => 'New complaint created using previous completed data!',
            ]);
        }

        // 4️⃣ OTHERWISE → Normal existing incomplete update OR new creation

        $existingComplaint = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 0)
            ->first();

        if ($existingComplaint) {
            $existingComplaint->update([
                'complainant_name' => $request->informer_name,
                'complainant_aadhar' => $request->informer_aadhar,
                'complainant_address' => $request->informer_address,
                'complainant_city' => $request->informer_city,
                'complainant_district' => $request->informer_district,
                'complainant_state' => $request->my_state,
                'complainant_email' => $request->informer_email ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Existing complaint updated successfully!',
            ]);
        }

        // NEW COMPLAINT
        $complaint = new Complainant;
        $complaint->secure_id = Str::random(32);
        $complaint->complainant_name = $request->informer_name;
        $complaint->complainant_aadhar = $request->informer_aadhar;
        $complaint->complainant_address = $request->informer_address;
        $complaint->complainant_city = $request->informer_city;
        $complaint->complainant_district = $request->informer_district;
        $complaint->complainant_email = $request->informer_email ?? null;
        $complaint->complainant_state = $request->my_state;
        $complaint->complainant_phone = $mobile;
        $complaint->user_id = auth()->id();
        $complaint->is_completed = 0;
        $complaint->save();

        return response()->json([
            'success' => true,
            'message' => 'New informer record created successfully!',
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
            'complaint_type' => $request->complaint_type,
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
        // dd($request->declaration,$request->toArray());
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
                'gstLocality' => 'required|string|max:255',
                'gstDistrict' => 'required|string|max:255',
                'gstCity' => 'required|string|max:255',
                'gstDescription' => 'required|string|max:150',
                'involvedType' => 'nullable|in:firm,vehicle',
                'gstFirmName' => 'nullable|string|regex:/^[a-zA-Z0-9\s]+$/u',
                'gstGstin' => [
                    'nullable',
                    'size:15',
                    'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i',
                ],
                'gstFirmAddress' => 'nullable|string',
                'gstVehicleNumber' => [
                    'nullable',               
                    'string',
                    'max:10',                  
                    'regex:/^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{1,4}$/i',
                ],
                'gstPersonName' =>  'nullable|string',
                'gstProof.*' => 'file|mimes:pdf,jpg,jpeg,png|max:1024',
                // 'declaration' => 'accepted', 
            ],

            // gstPersonName
            // involvedType



            'vat' => [
                'vatFirmName' => 'nullable|string|regex:/^[a-zA-Z0-9\s]+$/u',
                'vatTin' => [
                    'nullable',
                    'alpha_num',
                    'size:11',
                    'regex:/^[0-9A-Z]{11}$/i',
                ],
                'vatFirmAddress' => 'nullable|string',
                'vatLocality' => 'required|string|max:255',
                'vatDistrict' => 'required|string|max:255',
                'vatDescription' => 'required|string|max:150',
                'vatProof.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024', 
                'vatVehicleNumber' => [
                    'nullable',          
                    'string',
                    'max:10',           
                    'regex:/^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{1,4}$/i',
                ],

            ],
            'excise' => [
                'exciseName' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
                'exciseDetails' => 'required|string|max:2000',
                'exciseDesc' => 'nullable|string|max:255',
                'excisePlace' => 'required|string|max:255',
                'exciseTime' => 'required|string|max:255',
                'exciseProof.*' => 'file|mimes:pdf,jpg,jpeg,png|max:1024',
                'exciseCity' => 'required',
                'exciseVehicleNumber' => [
                    'nullable',
                    'string',
                    'max:10',
                    'regex:/^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{1,4}$/i',
                ],
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

        if (! $complaint) {
            return response()->json([
                'success' => false,
                'message' => 'No pending complaint found for update.',
            ]);
        }

        // 🔹 Generate Unique Application ID (if not set)
        // if (empty($complaint->application_id)) {
        //     do {
        //         $randomDigits = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        //         $applicationId = strtoupper($type) . '-' . $randomDigits;
        //     } while (Complainant::where('application_id', $applicationId)->exists());

        //     $complaint->application_id = $applicationId;
        // } else {
        //     $applicationId = $complaint->application_id;
        // }

        if (empty($complaint->application_id)) {
            do {
                $randomDigits = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

                // 🔹 Generate prefix based on complaint type
                switch (strtoupper($type)) {
                    case 'EXCISE':
                        $prefix = 'EXC';
                        break;
                    case 'GST':
                        $prefix = 'GST';
                        break;
                    case 'VAT':
                        $prefix = 'VAT';
                        break;
                    default:
                        $prefix = 'CMP';
                }

                $applicationId = $prefix.'-'.$randomDigits;

            } while (Complainant::where('application_id', $applicationId)->exists());

            $complaint->application_id = $applicationId;
        } else {
            $applicationId = $complaint->application_id;
        }

        $complaint->complaint_type = $type;

        // dd($request->declaration);

        // 🔹 GST Complaint Data
        if ($type === 'gst') {
            $complaint->gst_firm_name = $request->gstFirmName;
            $complaint->gst_gstin = strtoupper($request->gstGstin);
            $complaint->gst_firm_address = $request->gstFirmAddress;
            $complaint->gst_locality = $request->gstLocality;
            $complaint->district = $request->gstDistrict;
            $complaint->gst_description = $request->gstDescription;
            $complaint->gst_vehicle_number = $request->gstVehicleNumber ?? '';
            $complaint->declaration = $request->has('declaration') ? 1 : 0;
            $complaint->gst_person_name = $request->gstPersonName ?? '';
            $complaint->involved_type = $request->involvedType ?? '';
            $complaint->gst_city = $request->gstCity;


            // gstPersonName
            // involvedType

            if ($request->hasFile('gstProof')) {
                $files = $request->file('gstProof');

                if (count($files) > 5) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can upload a maximum of 5 files.',
                    ]);
                }

                $uploadedFiles = [];
                foreach ($files as $file) {
                    $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                    $file->storeAs("complaints/{$applicationId}", $fileName, 'public');
                    $uploadedFiles[] = $fileName;
                }
                $complaint->gst_proof = json_encode($uploadedFiles); // store as JSON
            }
        }

        // 🔹 VAT Complaint Data

        if ($type === 'vat') {
            $complaint->vat_locality = $request->vatLocality;
            $complaint->district = $request->vatDistrict;
            $complaint->vat_city = $request->vatCity;
            $complaint->vat_description = $request->vatDescription;
            $complaint->involved_type = $request->vatInvolvedType;
            $complaint->vat_firm_name = $request->vatFirmName;
            $complaint->vat_tin = strtoupper($request->vatTin);
            $complaint->vat_firm_address = $request->vatFirmAddress;
            $complaint->vat_vehicle_number = $request->vatVehicleNumber;
            $complaint->vat_person_name = $request->vatPersonName ?? '';
            $complaint->declaration = $request->has('declaration') ? 1 : 0;

            if ($request->hasFile('vatProof')) {
                $files = $request->file('vatProof');

                if (count($files) > 5) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can upload a maximum of 5 files.',
                    ]);
                }

                $uploadedFiles = [];
                foreach ($files as $file) {
                    $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                    $file->storeAs("complaints/{$applicationId}", $fileName, 'public');
                    $uploadedFiles[] = $fileName;
                }
                $complaint->vat_proof = json_encode($uploadedFiles);
            }
        }

        // 🔹 Excise Complaint Data
        if ($type === 'excise') {
            $complaint->excise_name = $request->exciseName;
            $complaint->excise_desc = $request->exciseDesc;
            $complaint->excise_place = $request->excisePlace;
            $complaint->excise_time = $request->exciseTime;
            $complaint->excise_details = $request->exciseDetails;
            $complaint->excise_vehicle_number = $request->exciseVehicleNumber ?? '';
            $complaint->district = $request->exciseDistrict;
            $complaint->excise_city = $request->exciseCity;
            $complaint->declaration = $request->has('declaration') ? 1 : 0;

            

            if ($request->hasFile('exciseProof')) {
                $files = $request->file('exciseProof');

                if (count($files) > 5) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can upload a maximum of 5 files.',
                    ]);
                }

                $uploadedFiles = [];
                foreach ($files as $file) {
                    $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                    $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
                    $uploadedFiles[] = $fileName;
                }

                // Save as JSON in database
                $complaint->excise_proof = json_encode($uploadedFiles);
            }
        }

        $complaint->is_completed = 1;
        $complaint->save();

        // 🔹 Update User Info if Missing
        $user = User::where('mobile', $mobile)->first();
        if ($user && (empty($user->aadhaar) || is_null($user->aadhaar))) {
            $user->update([
                'email' => $request->informerEmail ?? null,
                'aadhaar' => $request->informerAadhar,
                'address' => $request->informerAddress,
                'district' => 'Demo District',
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Complaint updated successfully. Your Application ID is: '.$complaint->application_id,
            'application_id' => $complaint->application_id,
        ]);
    }

    // public function getDistrict(Request $request)
    // {
    //     $userData = Complainant::where('complainant_phone', auth::user()->mobile)
    //         ->first();

    //     $stateId = $request->state_id ?? $userData->complainant_state;

    //     $districts = IndiaDistrict::where('state_id', $stateId)->get();

    //     return response()->json([
    //         'districts' => $districts,
    //         'selectedDistrict' => $userData->complainant_district ?? null,
    //         'selectedState' => $stateId
    //     ]);
    // }

    public function getDistrict(Request $request)
    {
        // Mobile number se data
        $userData = Complainant::where('complainant_phone', Auth::user()->mobile)->first();

        // State ID decide karo
        $stateId = $request->state_id
                    ?? ($userData->complainant_state ?? null);

        // Agar state hi nahi mila → blank return karo
        if (! $stateId) {
            return response()->json([
                'districts' => [],
                'selectedDistrict' => '',
                'selectedState' => '',
            ]);
        }

        // State ke according districts
        $districts = IndiaDistrict::where('state_id', $stateId)->get();

        return response()->json([
            'districts' => $districts,
            'selectedDistrict' => $userData->complainant_district ?? '',
            'selectedState' => $stateId ?? '',
        ]);
    }
}
