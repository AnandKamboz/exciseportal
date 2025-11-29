<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\District;
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

        // $districts = DB::table('districts')->get();
        $userMobile = Auth::user()->mobile;
        $userDataForNewApplication = Complainant::where('complainant_phone', $userMobile)->where('is_completed', 1)->first();
        $userData = Complainant::where('complainant_phone', $userMobile)->where('is_completed', 0)->first();
        $districts = DB::table('districts')
            ->orderBy('name', 'asc')
            ->get();

        $indiaStates = State::all();
        $haryanaDistrictsList = DB::table('india_districts')
            ->where('state_id', 8)
            ->orderBy('name', 'ASC')
            ->get();

        return view('complainant.create', compact('haryanaDistrictsList', 'indiaStates', 'userMobile', 'userData', 'districts', 'userDataForNewApplication', 'districts'));
    }

    public function saveInformer(Request $request)
    {
        // ---------------- VALIDATION ----------------
        $request->validate([
            'informer_name' => 'required|string|max:100',
            'informer_aadhar' => 'required|digits:12',
            'informer_address1' => 'required|string|max:255',
            'informer_address2' => 'required|string|max:255',
            'informer_state' => 'required',
            'informer_district' => 'required',
            'informer_email' => 'nullable|email',
        ]);

        $mobile = auth()->user()->mobile;

        // Combine address
        $fullAddress = $request->informer_address1.', '.$request->informer_address2;

        // ---------------- CHECK COMPLETED RECORD ----------------
        $completedRecord = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 1)
            ->first();

        if ($completedRecord) {

            // If incomplete exists → update
            $existingIncomplete = Complainant::where('complainant_phone', $mobile)
                ->where('is_completed', 0)
                ->first();

            if ($existingIncomplete) {
                $existingIncomplete->update([
                    'complainant_name' => $completedRecord->complainant_name,
                    'complainant_aadhar' => $completedRecord->complainant_aadhar,
                    'complainant_address1' => $completedRecord->complainant_address1,
                    'complainant_address2' => $completedRecord->complainant_address2,
                    'complainant_address' => $completedRecord->complainant_address,
                    'complainant_state' => $completedRecord->complainant_state,
                    'complainant_district' => $completedRecord->complainant_district,
                    'complainant_email' => $completedRecord->complainant_email,
                    'complaint_type' => 'gst', // ★ default value
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data auto-filled from previous completed complaint!',
                ]);
            }

            // Create NEW incomplete using completed data
            $complaint = new Complainant;
            $complaint->secure_id = Str::random(32);
            $complaint->complainant_name = $completedRecord->complainant_name;
            $complaint->complainant_aadhar = $completedRecord->complainant_aadhar;
            $complaint->complainant_address1 = $completedRecord->complainant_address1;
            $complaint->complainant_address2 = $completedRecord->complainant_address2;
            $complaint->complainant_address = $completedRecord->complainant_address;
            $complaint->complainant_state = $completedRecord->complainant_state;
            $complaint->complainant_district = $completedRecord->complainant_district;
            $complaint->complainant_email = $completedRecord->complainant_email;
            $complaint->complainant_phone = $mobile;
            $complaint->user_id = auth()->id();
            $complaint->complaint_type = 'gst'; // ★ default value
            $complaint->is_completed = 0;
            $complaint->save();

            return response()->json([
                'success' => true,
                'message' => 'New complaint created using previous completed data!',
            ]);
        }

        // ---------------- UPDATE EXISTING INCOMPLETE ----------------
        $existingComplaint = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 0)
            ->first();

        if ($existingComplaint) {
            $existingComplaint->update([
                'complainant_name' => $request->informer_name,
                'complainant_aadhar' => $request->informer_aadhar,
                'complainant_address1' => $request->informer_address1,
                'complainant_address2' => $request->informer_address2,
                'complainant_address' => $fullAddress,
                'complainant_state' => $request->informer_state,
                'complainant_district' => $request->informer_district,
                'complainant_email' => $request->informer_email,
                'complaint_type' => 'gst', // ★ ensure default
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Existing complaint updated successfully!',
            ]);
        }

        // ---------------- CREATE NEW COMPLAINT ----------------
        $complaint = new Complainant;
        $complaint->secure_id = Str::random(32);
        $complaint->complainant_name = $request->informer_name;
        $complaint->complainant_aadhar = $request->informer_aadhar;
        $complaint->complainant_address1 = $request->informer_address1;
        $complaint->complainant_address2 = $request->informer_address2;
        $complaint->complainant_address = $fullAddress;
        $complaint->complainant_state = $request->informer_state;
        $complaint->complainant_district = $request->informer_district;
        $complaint->complainant_email = $request->informer_email ?? null;
        $complaint->complainant_phone = $mobile;
        $complaint->user_id = auth()->id();
        $complaint->complaint_type = 'gst'; // ★ default value
        $complaint->is_completed = 0;

        $complaint->save();

        return response()->json([
            'success' => true,
            'message' => 'New informer record created successfully!',
        ]);
    }

    public function saveComplaintType(Request $request)
    {

        $request->complaint_type = 'gst';

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
    //     $request->merge([
    //         'taxType' => 'gst',
    //     ]);

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

    //     $rules = match ($type) {
    //         'gst' => [
    //             'complaintType' => 'required',
    //             'gstDescription' => 'required|string|max:150',
    //             'pincode' => 'required|digits:6',
    //             'involvedType' => 'nullable|in:firm,vehicle',
    //             'gstFirmName' => 'nullable|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'gstGstin' => [
    //                 'nullable',
    //                 'size:15',
    //                 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i',
    //             ],
    //             'gstFirmAddress' => 'nullable|string',
    //             'gstVehicleNumber' => [
    //                 'nullable',
    //                 'string',
    //                 'max:10',
    //                 'regex:/^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{1,4}$/i',
    //             ],
    //             'gstPersonName' => 'nullable|string',
    //             'gstProof.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
    //         ],

    //         'vat' => [
    //             'vatFirmName' => 'nullable|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'vatTin' => [
    //                 'nullable',
    //                 'alpha_num',
    //                 'size:11',
    //                 'regex:/^[0-9A-Z]{11}$/i',
    //             ],
    //             'vatFirmAddress' => 'nullable|string',
    //             'vatLocality' => 'required|string|max:255',
    //             'vatDistrict' => 'required|string|max:255',
    //             'vatDescription' => 'required|string|max:150',
    //             'vatProof.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
    //             'vatVehicleNumber' => [
    //                 'nullable',
    //                 'string',
    //                 'max:10',
    //                 'regex:/^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{1,4}$/i',
    //             ],

    //         ],
    //         'excise' => [
    //             'exciseName' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
    //             'exciseDetails' => 'required|string|max:2000',
    //             'exciseDesc' => 'nullable|string|max:255',
    //             'excisePlace' => 'required|string|max:255',
    //             'exciseTime' => 'required|string|max:255',
    //             'exciseProof.*' => 'file|mimes:pdf,jpg,jpeg,png|max:1024',
    //             'exciseCity' => 'required',
    //             'exciseVehicleNumber' => [
    //                 'nullable',
    //                 'string',
    //                 'max:10',
    //                 'regex:/^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{1,4}$/i',
    //             ],
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

    //     if (! $complaint) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No pending complaint found for update.',
    //         ]);
    //     }

    //     if (empty($complaint->application_id)) {
    //         $yearSuffix = now()->format('y');

    //         switch (strtoupper($type)) {
    //             case 'EXCISE':
    //                 $prefix = 'EXC';
    //                 break;
    //             case 'GST':
    //                 $prefix = 'GST';
    //                 break;
    //             case 'VAT':
    //                 $prefix = 'VAT';
    //                 break;
    //             default:
    //                 $prefix = 'CMP';
    //         }

    //         do {
    //             $randomDigits = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    //             $applicationId = "{$prefix}{$yearSuffix}-{$randomDigits}";

    //         } while (Complainant::where('application_id', $applicationId)->exists());

    //         $complaint->application_id = $applicationId;

    //     } else {
    //         $applicationId = $complaint->application_id;
    //     }

    //     $complaint->complaint_type = $type;

    //     // Here

    //     $pin = $request->pincode;
    //     $url = 'https://api.postalpincode.in/pincode/'.$pin;

    //     $curl = curl_init();

    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_SSL_VERIFYPEER => false,
    //         CURLOPT_SSL_VERIFYHOST => false,
    //         CURLOPT_TIMEOUT => 10,
    //     ]);

    //     $response = curl_exec($curl);

    //     if (curl_errno($curl)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unable to connect Postal API: '.curl_error($curl),
    //         ], 400);
    //     }

    //     curl_close($curl);

    //     $data = json_decode($response, true);

    //     if (empty($data) || $data[0]['Status'] !== 'Success') {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid PIN Code. Please enter a valid Haryana PIN Code.',
    //         ], 400);
    //     }

    //     $post = $data[0]['PostOffice'][0];

    //     $apiState = strtolower($post['State']);
    //     $apiDistrict = $post['District'];

    //     if ($apiState !== 'haryana') {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Please enter a valid Haryana PIN Code. This PIN Code does not belong to Haryana.',
    //         ], 400);
    //     }

    //     $normalizedApiDistrict = strtolower(str_replace([' ', '-', '_'], '', $apiDistrict));

    //     $districts = District::all();

    //     $matchedDistrict = null;

    //     foreach ($districts as $dis) {
    //         $normalizedDbName = strtolower(str_replace([' ', '-', '_'], '', $dis->name));

    //         if ($normalizedDbName === $normalizedApiDistrict) {
    //             $matchedDistrict = $dis;
    //             break;
    //         }
    //     }

    //     if (! $matchedDistrict) {
    //         $matchedDistrict = District::where('name', 'LIKE', '%'.$apiDistrict.'%')->first();
    //     }

    //     if (! $matchedDistrict) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'District not found in system. API district: '.$apiDistrict,
    //         ], 400);
    //     }

    //     if ($type === 'gst') {
    //         $complaint->type_of_complaint = $request->complaintType;
    //         $complaint->gst_description = $request->gstDescription;
    //         // $complaint->gst_building_no = $request->buildingNo;
    //         // $complaint->gst_street_name = $request->streetName;
    //         // $complaint->gst_address1 = $request->gst_address1;
    //         // $complaint->gst_address2 = $request->gst_address2;
    //         $complaint->pincode = $request->pincode;
    //         $complaint->gst_firm_name = $request->gstFirmName;
    //         $complaint->gst_gstin = strtoupper($request->gstGstin);
    //         $complaint->gst_firm_address = $request->gstFirmAddress;
    //         $complaint->gst_vehicle_number = $request->gstVehicleNumber ?? '';
    //         $complaint->declaration = $request->has('declaration') ? 1 : 0;
    //         $complaint->gst_person_name = $request->gstPersonName ?? '';
    //         $complaint->involved_type = $request->involvedType ?? '';
    //         $complaint->district_id = $matchedDistrict->id;
    //         $complaint->district_name = $matchedDistrict->name;

    //         if ($request->hasFile('gstProof')) {
    //             $files = $request->file('gstProof');

    //             if (count($files) > 5) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'You can upload a maximum of 5 files.',
    //                 ]);
    //             }

    //             $uploadedFiles = [];
    //             foreach ($files as $file) {
    //                 $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    //                 $file->storeAs("complaints/{$applicationId}", $fileName, 'public');
    //                 $uploadedFiles[] = $fileName;
    //             }
    //             $complaint->gst_proof = json_encode($uploadedFiles);
    //         }
    //     }

    //     // 🔹 VAT Complaint Data

    //     if ($type === 'vat') {
    //         $complaint->vat_locality = $request->vatLocality;
    //         $complaint->district = $request->vatDistrict;
    //         $complaint->vat_city = $request->vatCity;
    //         $complaint->vat_description = $request->vatDescription;
    //         $complaint->involved_type = $request->vatInvolvedType;
    //         $complaint->vat_firm_name = $request->vatFirmName;
    //         $complaint->vat_tin = strtoupper($request->vatTin);
    //         $complaint->vat_firm_address = $request->vatFirmAddress;
    //         $complaint->vat_vehicle_number = $request->vatVehicleNumber;
    //         $complaint->vat_person_name = $request->vatPersonName ?? '';
    //         $complaint->declaration = $request->has('declaration') ? 1 : 0;

    //         if ($request->hasFile('vatProof')) {
    //             $files = $request->file('vatProof');

    //             if (count($files) > 5) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'You can upload a maximum of 5 files.',
    //                 ]);
    //             }

    //             $uploadedFiles = [];
    //             foreach ($files as $file) {
    //                 $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    //                 $file->storeAs("complaints/{$applicationId}", $fileName, 'public');
    //                 $uploadedFiles[] = $fileName;
    //             }
    //             $complaint->vat_proof = json_encode($uploadedFiles);
    //         }
    //     }

    //     // 🔹 Excise Complaint Data
    //     if ($type === 'excise') {
    //         $complaint->excise_name = $request->exciseName;
    //         $complaint->excise_desc = $request->exciseDesc;
    //         $complaint->excise_place = $request->excisePlace;
    //         $complaint->excise_time = $request->exciseTime;
    //         $complaint->excise_details = $request->exciseDetails;
    //         $complaint->excise_vehicle_number = $request->exciseVehicleNumber ?? '';
    //         $complaint->district = $request->exciseDistrict;
    //         $complaint->excise_city = $request->exciseCity;
    //         $complaint->declaration = $request->has('declaration') ? 1 : 0;

    //         if ($request->hasFile('exciseProof')) {
    //             $files = $request->file('exciseProof');

    //             if (count($files) > 5) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'You can upload a maximum of 5 files.',
    //                 ]);
    //             }

    //             $uploadedFiles = [];
    //             foreach ($files as $file) {
    //                 $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    //                 $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
    //                 $uploadedFiles[] = $fileName;
    //             }

    //             // Save as JSON in database
    //             $complaint->excise_proof = json_encode($uploadedFiles);
    //         }
    //     }

    //     $complaint->is_completed = 1;
    //     $complaint->save();

    //     // 🔹 Update User Info if Missing
    //     $user = User::where('mobile', $mobile)->first();
    //     if ($user && (empty($user->aadhaar) || is_null($user->aadhaar))) {
    //         $user->update([
    //             'email' => $request->informerEmail ?? null,
    //             'aadhaar' => $request->informerAadhar,
    //             'address' => $request->informerAddress,
    //             'district' => 'Demo District',
    //             'updated_at' => now(),
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Complaint updated successfully. Your Application ID is: '.$complaint->application_id,
    //         'application_id' => $complaint->application_id,
    //     ]);
    // }

    public function submitComplaint(Request $request)
    {
        $request->merge([
            'taxType' => 'gst',
        ]);

        $validator = Validator::make($request->all(), [
            'taxType' => 'required|in:gst,vat,excise',
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
                'complaintType' => 'required',
                'gstDescription' => 'required|string|max:150',
                'location' => 'required',
                'pincode' => 'required|digits:6',
                'gstFirmName' => 'nullable|string',
                'gstGstin' => 'nullable|size:15',
                'gstFirmAddress' => 'nullable|string',
                'gstVehicleNumber' => 'nullable|string|max:10',
                'gstProof.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:1024',
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

        // find complaint
        $complaint = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 0)->first();

        if (! $complaint) {
            return response()->json([
                'success' => false,
                'message' => 'No pending complaint found for update.',
            ]);
        }

        // generate or reuse application ID
        if (empty($complaint->application_id)) {

            $yearSuffix = now()->format('y');
            $prefix = strtoupper($type);

            do {
                $applicationId = $prefix.$yearSuffix.'-'.mt_rand(100000, 999999);
            } while (Complainant::where('application_id', $applicationId)->exists());

            $complaint->application_id = $applicationId;
        }

        $complaint->complaint_type = $type;

        // **********************************************
        //  PIN CODE VALIDATION
        // **********************************************

        $pin = $request->pincode;
        $url = 'https://api.postalpincode.in/pincode/'.$pin;

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (empty($data) || $data[0]['Status'] !== 'Success') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Haryana PIN Code.',
            ], 400);
        }

        $post = $data[0]['PostOffice'][0];

        $apiState = strtolower($post['State']);
        $apiDistrict = $post['District'];

        if ($apiState !== 'haryana') {
            return response()->json([
                'success' => false,
                'message' => 'PIN Code does not belong to Haryana.',
            ], 400);
        }

        // normalize API district for matching
        $normalizedApi = strtolower(str_replace([' ', '-', '_'], '', $apiDistrict));

        $districts = District::all();
        $matchedDistrict = null;

        foreach ($districts as $dis) {
            $normalizedDB = strtolower(str_replace([' ', '-', '_'], '', $dis->name));
            if ($normalizedDB === $normalizedApi) {
                $matchedDistrict = $dis;
                break;
            }
        }

        if (! $matchedDistrict) {
            return response()->json([
                'success' => false,
                'message' => 'District not found in system for this pincode: '.$apiDistrict,
            ], 400);
        }

        // 💡 Final IMPORTANT CHECK
        if ($request->district != $matchedDistrict->id) {
            return response()->json([
                'success' => false,
                'message' => 'District mismatch! Pincode belongs to '.$apiDistrict,
            ], 400);
        }

        // **********************************************
        //      SAVE GST COMPLAINT DATA
        // **********************************************

        if ($type === 'gst') {
            $complaint->type_of_complaint = $request->complaintType;
            $complaint->gst_description = $request->gstDescription;
            $complaint->location = $request->location;
            $complaint->pincode = $request->pincode;
            $complaint->gst_firm_name = $request->gstFirmName;
            $complaint->gst_gstin = strtoupper($request->gstGstin);
            $complaint->gst_firm_address = $request->gstFirmAddress;
            $complaint->gst_vehicle_number = $request->gstVehicleNumber ?? '';
            $complaint->involved_type = $request->involvedType ?? '';
            $complaint->district_id = $matchedDistrict->id;
            $complaint->district_name = $matchedDistrict->name;

            // files
            if ($request->hasFile('gstProof')) {
                if (count($request->file('gstProof')) > 5) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Max 5 files allowed',
                    ]);
                }

                foreach ($request->file('gstProof') as $file) {
                    $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                    $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
                    $files[] = $fileName;
                }

                $complaint->gst_proof = json_encode($files);
            }
        }

        // mark done
        $complaint->is_completed = 1;
        $complaint->save();

        return response()->json([
            'success' => true,
            'message' => 'Complaint Updated. Your Application ID: '.$complaint->application_id,
            'application_id' => $complaint->application_id,
        ]);
    }

    public function getDistrict(Request $request)
    {
        $userData = Complainant::where('complainant_phone', Auth::user()->mobile)->first();

        $stateId = $request->state_id
                    ?? ($userData->complainant_state ?? '8');

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
