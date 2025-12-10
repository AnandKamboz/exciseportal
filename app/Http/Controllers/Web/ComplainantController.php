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
use Illuminate\Validation\Rule;

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
        // $districts = DB::table('districts')
        //     ->orderBy('name', 'asc')
        //     ->get();

        $districts = DB::table('districts')
            ->where('id', '<=', 22)
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
            // 'informer_email' => [
            //     'nullable',
            //     Rule::unique('complainants', 'complainant_email')->ignore(auth()->id(), 'user_id'),
            // ],
            'informer_email' => 'nullable|email',
            'informer_state' => ['required', 'digits_between:1,2', 'numeric'],
            'informer_district' => ['required', 'digits_between:1,3', 'numeric'],
            'informer_address1' => 'required|string|max:255',
            'informer_address2' => 'required|string|max:255',
        ]);

        $mobile = auth()->user()->mobile;
        $fullAddress = $request->informer_address1.', '.$request->informer_address2;

        // ---------------- CHECK COMPLETED RECORD ----------------
        $completedRecord = Complainant::where('complainant_phone', $mobile)
            ->where('is_completed', 1)
            ->first();

        if ($completedRecord) {
            $existingIncomplete = Complainant::where('complainant_phone', $mobile)
                ->where('is_completed', 0)
                ->first();

            if ($existingIncomplete) {
                $existingIncomplete->update([
                    'complainant_name' => $completedRecord->complainant_name,
                    'complainant_aadhar' => '',
                    'complainant_address1' => $completedRecord->complainant_address1,
                    'complainant_address2' => $completedRecord->complainant_address2,
                    'complainant_address' => $completedRecord->complainant_address,
                    'complainant_state' => $completedRecord->complainant_state,
                    'complainant_district' => $completedRecord->complainant_district,
                    'complainant_email' => $completedRecord->complainant_email,
                    'complaint_type' => 'gst',
                    'current_step' => ($existingIncomplete->current_step == 1) ? 2 : $existingIncomplete->current_step,

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
            $complaint->complaint_type = 'gst';
            $complaint->is_completed = 0;
            $complaint->current_step = 2;
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
                'complaint_type' => 'gst',
                'current_step' => ($existingComplaint->current_step == 1) ? 2 : $existingComplaint->current_step,
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
        $complaint->complaint_type = 'gst';
        $complaint->is_completed = 0;
        $complaint->current_step = 2;
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
    //     // dd($request->toArray());
    //     $request->merge([
    //         'taxType' => 'gst',
    //     ]);

    //     $validator = Validator::make($request->all(), [
    //         'taxType' => 'required|in:gst,vat,excise',
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
    //             'complaintType' => [
    //                 'required',
    //                 'string',
    //                 'in:wrong_rate_of_tax,bill/invoice_not_issued,under_reporting,fake_itc,not_obtained_gstin,any_other',
    //             ],
    //             'gstDescription' => 'required|string|max:150',
    //             'location' => 'required|max:150',
    //             'district' => ['required', 'numeric', 'digits_between:1,2'],
    //             'pincode' => 'required|numeric:6',
    //             'gstProof.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:1024',
    //             'involvedType' => ['nullable', 'string', 'in:firm,vehicle'],

    //             'gstFirmName' => 'nullable|string',
    //             'gstGstin' => 'nullable|size:15',
    //             'gstFirmAddress' => 'nullable|string',

    //             'gstVehicleNumber' => 'nullable|string|max:10',
    //             'gstPersonName' => 'nullable|string|max:50',

    //             'declaration' => 'required|in:1',
    //         ],
    //         default => [],
    //     };

    //     $firmGroup = [
    //         $request->gstFirmName,
    //         $request->gstGstin,
    //         $request->gstFirmAddress,
    //     ];

    //     $vehicleGroup = [
    //         $request->gstVehicleNumber,
    //         $request->gstPersonName,
    //     ];

    //     $firmFilled = collect($firmGroup)->filter()->isNotEmpty();
    //     $vehicleFilled = collect($vehicleGroup)->filter()->isNotEmpty();

    //     if ($firmFilled && $vehicleFilled) {
    //         return back()->withErrors([
    //             'error' => 'You cannot fill both Firm and Vehicle details together.',
    //         ])->withInput();
    //     }

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ]);
    //     }

    //     $complaint = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 0)->first();

    //     if (! $complaint) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No pending complaint found for update.',
    //         ]);
    //     }

    //     if (empty($complaint->application_id)) {

    //         $yearSuffix = now()->format('y');
    //         $prefix = strtoupper($type);

    //         do {
    //             $applicationId = $prefix.$yearSuffix.'-'.mt_rand(100000, 999999);
    //         } while (Complainant::where('application_id', $applicationId)->exists());

    //         $complaint->application_id = $applicationId;
    //     }

    //     $complaint->complaint_type = $type;

    //     // **********************************************
    //     //  PIN CODE VALIDATION
    //     // **********************************************

    //     // $pin = $request->pincode;
    //     // $url = 'https://api.postalpincode.in/pincode/'.$pin;

    //     // $response = file_get_contents($url);
    //     // $data = json_decode($response, true);

    //     // if (empty($data) || $data[0]['Status'] !== 'Success') {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'Invalid Haryana PIN Code.',
    //     //     ], 400);
    //     // }

    //     // $post = $data[0]['PostOffice'][0];

    //     // $apiState = strtolower($post['State']);
    //     // $apiDistrict = $post['District'];

    //     // if ($apiState !== 'haryana') {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'PIN Code does not belong to Haryana.',
    //     //     ], 400);
    //     // }

    //     // $normalizedApi = strtolower(str_replace([' ', '-', '_'], '', $apiDistrict));

    //     // $districts = District::all();
    //     // $matchedDistrict = null;

    //     // foreach ($districts as $dis) {
    //     //     $normalizedDB = strtolower(str_replace([' ', '-', '_'], '', $dis->name));
    //     //     if ($normalizedDB === $normalizedApi) {
    //     //         $matchedDistrict = $dis;
    //     //         break;
    //     //     }
    //     // }

    //     // if (! $matchedDistrict) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'District not found in system for this pincode: '.$apiDistrict,
    //     //     ], 400);
    //     // }

    //     // if ($request->district != $matchedDistrict->id) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'District mismatch! Pincode belongs to '.$apiDistrict,
    //     //     ], 400);
    //     // }

    //     // **********************************************
    //     //      SAVE GST COMPLAINT DATA
    //     // **********************************************

    //     $districtInfo = DB::table('districts')
    //         ->where('id', $request->district)
    //         ->first();

    //     if ($type === 'gst') {
    //         $complaint->type_of_complaint = $request->complaintType;
    //         $complaint->gst_description = $request->gstDescription;
    //         $complaint->location = $request->location;
    //         $complaint->pincode = $request->pincode;
    //         $complaint->gst_firm_name = $request->gstFirmName;
    //         $complaint->gst_gstin = strtoupper($request->gstGstin);
    //         $complaint->gst_firm_address = $request->gstFirmAddress;
    //         $complaint->gst_vehicle_number = $request->gstVehicleNumber ?? '';
    //         $complaint->gst_person_name = $request->gstPersonName ?? '';
    //         $complaint->involved_type = $request->involvedType ?? '';
    //         $complaint->district_id = $request->district;
    //         $complaint->district_name = $districtInfo->name;
    //         $complaint->declaration = '1';

    //         // files
    //         if ($request->hasFile('gstProof')) {
    //             if (count($request->file('gstProof')) > 5) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Max 5 files allowed',
    //                 ]);
    //             }

    //             foreach ($request->file('gstProof') as $file) {
    //                 $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    //                 $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
    //                 $files[] = $fileName;
    //             }

    //             $complaint->gst_proof = json_encode($files);
    //         }
    //     }

    //     $complaint->is_completed = 1;
    //     $complaint->save();

    //     $information = Complainant::where('complainant_phone', auth::user()->mobile)
    //         ->orderByDesc('is_completed')
    //         ->first();

    //     $user_details = User::where('mobile',auth::user()->mobile)->first();
    //     $user_name = $user_details->name;
    //     if(!$user_name){
    //         User::where('mobile',auth::user()->mobile)->update([
    //             "name" => $information->complainant_name,
    //             "email" => $information->complainant_email ?? "",
    //             'address' => $information->complainant_address,
    //             'district' =>  $information->district_id,
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Complaint Updated. Your Application ID: '.$complaint->application_id,
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
                'informerName' => 'required|string|max:100',
                'my_state' => ['required', 'digits_between:1,2', 'numeric'],
                'di' => ['required', 'digits_between:1,3', 'numeric'],
                'address1' => 'required|string|max:255',
                'address2' => 'required|string|max:255',
                'complaintType' => [
                    'required',
                    'string',
                    'in:wrong_rate_of_tax,bill/invoice_not_issued,under_reporting,fake_itc,not_obtained_gstin,any_other',
                ],
                'gstDescription' => 'required|string|max:200',
                'location' => 'required|max:150',
                'district' => ['required', 'numeric', 'digits_between:1,2'],
                'pincode' => 'nullable|digits:6',
                'gstProof.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:1024',
                'gstFirmName' => 'nullable|string',
                'gstGstin' => 'nullable|size:15',
                'gstFirmAddress' => 'nullable|string',
                'declaration' => 'required|in:1',
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
            ->where('is_completed', 0)->first();

        // STEP VALIDATION

        if (! $complaint) {
            return response()->json([
                'success' => false,
                'message' => 'No pending complaint found for update.',
            ]);
        }

        if (! isset($complaint->current_step) || $complaint->current_step < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete Step 1 first.',
            ]);
        }

        // ================================
        //  START TRANSACTION
        // ================================
        DB::beginTransaction();

        try {
            if (empty($complaint->application_id)) {

                $yearSuffix = now()->format('y');
                $prefix = strtoupper($type);

                do {
                    $applicationId = $prefix.$yearSuffix.'-'.mt_rand(100000, 999999);
                } while (Complainant::where('application_id', $applicationId)->exists());

                $complaint->application_id = $applicationId;
            }

            $complaint->complaint_type = $type;

            $districtInfo = DB::table('districts')
                ->where('id', $request->district)
                ->first();

            // GST DATA SAVE
            if ($type === 'gst') {
                $complaint->type_of_complaint = $request->complaintType;
                $complaint->gst_description = $request->gstDescription;
                $complaint->location = $request->location;
                $complaint->pincode = $request->pincode;
                $complaint->gst_firm_name = $request->gstFirmName;
                $complaint->gst_gstin = strtoupper($request->gstGstin);
                $complaint->gst_firm_address = $request->gstFirmAddress;
                $complaint->district_id = $request->district;
                $complaint->district_name = $districtInfo->name;
                $complaint->declaration = '1';

                // FILE UPLOAD
                $files = [];

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

            // Complete complaint
            $complaint->is_completed = 1;
            $complaint->save();

            $information = Complainant::where('complainant_phone', auth()->user()->mobile)
                ->orderByDesc('is_completed')
                ->first();

            $user_details = User::where('mobile', auth()->user()->mobile)->first();
            $user_name = $user_details->name;

            if (! $user_name) {
                User::where('mobile', auth()->user()->mobile)->update([
                    'name' => $information->complainant_name,
                    'email' => $information->complainant_email ?? '',
                    'address' => $information->complainant_address,
                    'district' => $information->district_id,
                ]);
            }

            // ================================
            // COMMIT TRANSACTION
            // ================================
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Complaint Updated. Your Application ID: '.$complaint->application_id,
                'application_id' => $complaint->application_id,
            ]);

        } catch (\Exception $e) {

            // ================================
            // ROLLBACK ON ERROR
            // ================================
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: '.$e->getMessage(),
            ]);
        }
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

    public function updateMissingInfoApi(Request $request, $secure_id)
    {
        $complainant = Complainant::where('secure_id', $secure_id)->first();

        if (! $complainant) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found.',
            ], 404);
        }

        $action = DetcAction::where('complaint_id', $complainant->id)->first();

        if (! $action) {
            return response()->json([
                'status' => false,
                'message' => 'No DETC action found for this complaint.',
            ], 404);
        }

        // Validation + Save based on missing_info
        if ($action->missing_info == 'gst_number') {

            $request->validate([
                'missing_gst_number' => 'required|string|max:255',
            ]);

            $complainant->missing_gst_number = $request->missing_gst_number;
        }

        if ($action->missing_info == 'firm_location') {

            $request->validate([
                'missing_firm_location' => 'required|string|max:255',
            ]);

            $complainant->missing_firm_location = $request->missing_firm_location;
        }

        if ($action->missing_info == 'address') {

            $request->validate([
                'missing_address' => 'required|string|max:255',
            ]);

            $complainant->missing_address = $request->missing_address;
        }

        $complainant->save();

        return response()->json([
            'status' => true,
            'message' => 'Missing information updated successfully.',
            'data' => $complainant,
        ], 200);
    }


    public function dashboarda()
    {
      $action = DetcAction::where('ward_no', auth()->user()->ward_no)->first();
      $informations = $action ? Complainant::where('id', $action->complaint_id)->get() : collect();
      return view('eto.dashboard', compact('informations'));
    }
}
