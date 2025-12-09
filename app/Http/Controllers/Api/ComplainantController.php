<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\DetcAction;
use App\Models\District;
use App\Models\IndiaDistrict;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ComplainantController extends Controller
{
    public function getStates()
    {
        $states = State::select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'status' => true,
            'states' => $states,
        ], 200);
    }

    public function getDistricts($state_id)
    {
        $districts = IndiaDistrict::where('state_id', $state_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'districts' => $districts,
        ], 200);
    }

    // Here

    public function saveInformer(Request $request)
    {
        try {
            // VALIDATION
            $validator = Validator::make($request->all(), [
                'informer_name' => 'required|string|max:100',
                'informer_email' => 'nullable|email',
                'informer_state' => ['nullable', 'numeric'],
                'informer_district' => ['nullable', 'numeric'],
                'informer_address1' => 'nullable|string|max:255',
                'informer_address2' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $mobile = auth()->user()->mobile;
            $fullAddress = $request->informer_address1.', '.$request->informer_address2 ?? '';

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
                        // 'complainant_address1' => $completedRecord->complainant_address1,
                        // 'complainant_address2' => $completedRecord->complainant_address2,
                        // 'complainant_address' => $completedRecord->complainant_address,
                        // 'complainant_state' => $completedRecord->complainant_state,
                        // 'complainant_district' => $completedRecord->complainant_district,
                        'complainant_email' => $completedRecord->complainant_email ?? '',
                        'complaint_type' => 'gst',
                        'current_step' => ($existingIncomplete->current_step == 1) ? 2 : $existingIncomplete->current_step,
                    ]);

                    return response()->json([
                        'status' => true,
                        'message' => 'Data auto-filled from previous completed complaint!',
                    ]);
                }

                Complainant::create([
                    'secure_id' => Str::random(32),
                    'complainant_name' => $completedRecord->complainant_name,
                    // 'complainant_aadhar' => $completedRecord->complainant_aadhar,
                    // 'complainant_address1' => $completedRecord->complainant_address1,
                    // 'complainant_address2' => $completedRecord->complainant_address2,
                    // 'complainant_address' => $completedRecord->complainant_address,
                    // 'complainant_state' => $completedRecord->complainant_state,
                    // 'complainant_district' => $completedRecord->complainant_district,
                    'complainant_email' => $completedRecord->complainant_email,
                    'complainant_phone' => $mobile,
                    'user_id' => auth()->id(),
                    'complaint_type' => 'gst',
                    'is_completed' => 0,
                    'current_step' => 2,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'New complaint created using previous completed data!',
                ]);
            }

            // CASE-2: If INCOMPLETE exists update
            $existingComplaint = Complainant::where('complainant_phone', $mobile)
                ->where('is_completed', 0)
                ->first();

            if ($existingComplaint) {
                $existingComplaint->update([
                    'complainant_name' => $request->informer_name,
                    // 'complainant_aadhar' => $request->informer_aadhar,
                    // 'complainant_address1' => $request->informer_address1,
                    // 'complainant_address2' => $request->informer_address2,
                    // 'complainant_address' => $fullAddress,
                    // 'complainant_state' => $request->informer_state,
                    // 'complainant_district' => $request->informer_district,
                    'complainant_email' => $request->informer_email,
                    'complaint_type' => 'gst',
                    'current_step' => ($existingComplaint->current_step == 1) ? 2 : $existingComplaint->current_step,

                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Complaint updated successfully!',
                ]);
            }

            // CASE-3: brand new complaint
            Complainant::create([
                'secure_id' => Str::random(32),
                'complainant_name' => $request->informer_name,
                // 'complainant_aadhar' => $request->informer_aadhar,
                // 'complainant_address1' => $request->informer_address1,
                // 'complainant_address2' => $request->informer_address2,
                // 'complainant_address' => $fullAddress,
                // 'complainant_state' => $request->informer_state,
                // 'complainant_district' => $request->informer_district,
                'complainant_email' => $request->informer_email,
                'complainant_phone' => $mobile,
                'user_id' => auth()->id(),
                'complaint_type' => 'gst',
                'is_completed' => 0,
                'current_step' => 2,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'New informer record created successfully!',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Here

    public function getHaryanaDistrict()
    {
        // $districts = District::all();
        $districts = DB::table('districts')
            ->where('id', '<=', 22)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $districts,
        ]);
    }

    // public function submitComplaint(Request $request)
    // {
    //     $request->merge(['taxType' => 'gst']);

    //     $validator = Validator::make($request->all(), [
    //         'taxType' => 'required|in:gst,vat,excise',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     $type = strtolower($request->taxType);
    //     $mobile = Auth::user()->mobile;

    //     $rules = [
    //         'complaintType' => [
    //             'required', 'string',
    //             'in:wrong_rate_of_tax,bill/invoice_not_issued,under_reporting,fake_itc,not_obtained_gstin,any_other',
    //         ],
    //         'gstDescription' => 'required|string|max:150',
    //         'location' => 'required|max:150',
    //         'district' => 'required|numeric',
    //         'pincode' => 'required|numeric',
    //         'gstProof.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:1024',
    //         'involvedType' => 'required|string|in:firm,vehicle',
    //         'declaration' => 'required|in:1',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     $complaint = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 0)
    //         ->first();

    //     if (! $complaint) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No pending complaint found for update.',
    //         ], 404);
    //     }

    //     if (empty($complaint->application_id)) {

    //         $prefix = strtoupper($type);
    //         $yearSuffix = now()->format('y');

    //         do {
    //             $applicationId = $prefix.$yearSuffix.'-'.mt_rand(100000, 999999);
    //         } while (Complainant::where('application_id', $applicationId)->exists());

    //         $complaint->application_id = $applicationId;
    //     }

    //     $districtData = DB::table('districts')
    //         ->where('id', $request->district)
    //         ->first();

    //     if (! $districtData) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid district.',
    //         ], 400);
    //     }

    //     $complaint->complaint_type = $type;
    //     $complaint->type_of_complaint = $request->complaintType;
    //     $complaint->gst_description = $request->gstDescription;
    //     $complaint->location = $request->location;
    //     $complaint->pincode = $request->pincode;
    //     $complaint->gst_firm_name = $request->gstFirmName;
    //     $complaint->gst_gstin = strtoupper($request->gstGstin);
    //     $complaint->gst_firm_address = $request->gstFirmAddress;
    //     $complaint->gst_vehicle_number = $request->gstVehicleNumber ?? '';
    //     $complaint->gst_person_name = $request->gstPersonName ?? '';
    //     $complaint->involved_type = $request->involvedType;
    //     $complaint->district_id = $districtData->id;
    //     $complaint->district_name = $districtData->name;
    //     $complaint->declaration = '1';

    //     if ($request->hasFile('gstProof')) {

    //         $files = [];

    //         if (count($request->file('gstProof')) > 5) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Max 5 files allowed',
    //             ]);
    //         }

    //         foreach ($request->file('gstProof') as $file) {
    //             $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    //             $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
    //             $files[] = $fileName;
    //         }

    //         $complaint->gst_proof = json_encode($files);
    //     }

    //     $complaint->is_completed = 1;
    //     $complaint->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Complaint submitted successfully.',
    //         'application_id' => $complaint->application_id,
    //     ], 200);
    // }

    // public function submitComplaint(Request $request)
    // {
    //     $files = $request->file('gstProof');

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
    //         ], 422);
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

    //     // *************** SAME VALIDATION ***************
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
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'You cannot fill both Firm and Vehicle details together.',
    //         ], 422);
    //     }

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     // *************** SAME LOGIC ***************
    //     $complaint = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 0)
    //         ->first();

    //     if (! $complaint) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No pending complaint found for update.',
    //         ], 404);
    //     }

    //     //  dd('pppppppppppppttttttttttttttttt');

    //     if (empty($complaint->application_id)) {

    //         $yearSuffix = now()->format('y');
    //         $prefix = strtoupper($type);

    //         do {
    //             $applicationId = $prefix.$yearSuffix.'-'.mt_rand(100000, 999999);
    //         } while (Complainant::where('application_id', $applicationId)->exists());

    //         $complaint->application_id = $applicationId;
    //     }

    //     $complaint->complaint_type = $type;

    //     $districtInfo = DB::table('districts')
    //         ->where('id', $request->district)
    //         ->first();

    //     if (! $districtInfo) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid district.',
    //         ], 400);
    //     }

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

    //         if ($request->hasFile('gstProof')) {
    //             $uploadedFiles = [];
    //             $files = $request->file('gstProof');

    //             $files = is_array($files) ? $files : [$files];

    //             if (count($files) > 5) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Max 5 files allowed.',
    //                 ], 400);
    //             }

    //             foreach ($files as $file) {
    //                 $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    //                 $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
    //                 $uploadedFiles[] = $fileName;
    //             }

    //             $complaint->gst_proof = json_encode($uploadedFiles);
    //         }

    //     }

    //     $complaint->is_completed = 1;
    //     $complaint->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Complaint Updated. Your Application ID: '.$complaint->application_id,
    //         'application_id' => $complaint->application_id,
    //     ]);
    // }

    // public function submitComplaint(Request $request)
    // {

    //     $files = $request->file('gstProof');

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
    //         ], 422);
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
    //             'gstDescription' => 'required|string|max:200',
    //             'location' => 'required|max:150',
    //             'district' => ['required', 'numeric', 'digits_between:1,2'],
    //             'pincode' => 'nullable|digits:6',
    //             'gstProof.*' => 'nullable|mimes:jpg,jpeg,png|max:10240',
    //             'gstFirmName' => 'nullable|string',
    //             'gstGstin' => 'nullable|size:15',
    //             'gstFirmAddress' => 'nullable|string',
    //             'declaration' => 'required|in:1',
    //         ],
    //         default => [],
    //     };

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     $complaint = Complainant::where('complainant_phone', $mobile)
    //         ->where('is_completed', 0)
    //         ->first();

    //     if (! $complaint) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No pending complaint found for update.',
    //         ], 404);
    //     }

    //     if (! isset($complaint->current_step) || $complaint->current_step <= 1) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Please complete Step 1 first.',
    //         ]);
    //     }

    //     DB::beginTransaction();

    //     try {

    //         if (empty($complaint->application_id)) {
    //             $yearSuffix = now()->format('y');
    //             $prefix = strtoupper($type);

    //             do {
    //                 $applicationId = $prefix.$yearSuffix.'-'.mt_rand(100000, 999999);
    //             } while (Complainant::where('application_id', $applicationId)->exists());

    //             $complaint->application_id = $applicationId;
    //         }

    //         $complaint->complaint_type = $type;

    //         $districtInfo = DB::table('districts')
    //             ->where('id', $request->district)
    //             ->first();

    //         if (! $districtInfo) {
    //             DB::rollBack();

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid district.',
    //             ], 400);
    //         }

    //         if ($type === 'gst') {
    //             $complaint->type_of_complaint = $request->complaintType ?? '';
    //             $complaint->gst_description = $request->gstDescription ?? '';
    //             $complaint->location = $request->location ?? '';
    //             $complaint->pincode = $request->pincode ?? '';
    //             $complaint->gst_firm_name = $request->gstFirmName ?? '';
    //             $complaint->gst_gstin = strtoupper($request->gstGstin) ?? '';
    //             $complaint->gst_firm_address = $request->gstFirmAddress ?? '';
    //             $complaint->district_id = $request->district ?? '';
    //             $complaint->district_name = $districtInfo->name ?? '';
    //             $complaint->declaration = '1';

    //             if ($request->hasFile('gstProof')) {
    //                 $uploadedFiles = [];
    //                 $files = $request->file('gstProof');
    //                 $files = is_array($files) ? $files : [$files];

    //                 if (count($files) > 5) {
    //                     DB::rollBack();

    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Max 5 files allowed.',
    //                     ], 400);
    //                 }

    //                 foreach ($files as $file) {
    //                     $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    //                     $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
    //                     $uploadedFiles[] = $fileName;
    //                 }

    //                 $complaint->gst_proof = json_encode($uploadedFiles);
    //             }
    //         }

    //         $complaint->is_completed = 1;
    //         $complaint->save();

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Complaint Updated. Your Application ID: '.$complaint->application_id,
    //             'application_id' => $complaint->application_id,
    //         ]);

    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong: '.$e->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function submitComplaint(Request $request)
    // {
    //     $files = $request->file('gstProof');

    //     // Always set GST as tax type here
    //     $request->merge([
    //         'taxType' => 'gst',
    //     ]);

    //     // Basic validation
    //     $validator = Validator::make($request->all(), [
    //         'taxType' => 'required|in:gst,vat,excise',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     $type = strtolower($request->taxType);
    //     $mobile = Auth::user()->mobile;

    //     // GST rules
    //     $rules = match ($type) {
    //         'gst' => [
    //             'complaintType' => [
    //                 'required',
    //                 'string',
    //                 'in:wrong_rate_of_tax,bill/invoice_not_issued,under_reporting,fake_itc,not_obtained_gstin,any_other',
    //             ],
    //             'gstDescription' => 'required|string|max:200',
    //             'location' => 'required|max:150',
    //             'district' => ['required', 'numeric', 'digits_between:1,2'],
    //             'pincode' => 'nullable|digits:6',
    //             'gstProof.*' => 'nullable|mimes:jpg,jpeg,png|max:10240',
    //             'gstFirmName' => 'nullable|string',
    //             'gstGstin' => 'nullable|size:15',
    //             'gstFirmAddress' => 'nullable|string',
    //             'declaration' => 'required|in:1',
    //         ],
    //         default => [],
    //     };

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }

    //     DB::beginTransaction();

    //     try {

    //         /**
    //          * STEP A → If user already has a COMPLETED application
    //          *           then auto-create a NEW application
    //          */
    //         $completedComplaint = Complainant::where('complainant_phone', $mobile)
    //             ->where('is_completed', 1)
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         if ($completedComplaint) {

    //             // Create NEW complaint using old user's data
    //             $complaint = new Complainant;
    //             $complaint->complainant_name = $completedComplaint->complainant_name;
    //             $complaint->complainant_phone = $completedComplaint->complainant_phone;
    //             $complaint->complainant_email = $completedComplaint->complainant_email;
    //             $complaint->current_step = 2;
    //             $complaint->is_completed = 0;
    //             $complaint->save(); // generate ID

    //         } else {

    //             /**
    //              * STEP B → Check if there's a pending application
    //              */
    //             $complaint = Complainant::where('complainant_phone', $mobile)
    //                 ->where('is_completed', 0)
    //                 ->first();
    //         }

    //         /**
    //          * STEP C → If no pending NEW application created or found, throw error
    //          */
    //         if (! $complaint) {
    //             DB::rollBack();

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No pending complaint found for update.',
    //             ], 404);
    //         }

    //         /**
    //          * STEP D → Ensure Step 1 completed
    //          */
    //         if (! isset($complaint->current_step) || $complaint->current_step <= 1) {
    //             DB::rollBack();

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Please complete Step 1 first.',
    //             ]);
    //         }

    //         /**
    //          * Create application ID if not already created
    //          */
    //         if (empty($complaint->application_id)) {
    //             $yearSuffix = now()->format('y');
    //             $prefix = strtoupper($type);

    //             do {
    //                 $applicationId = $prefix.$yearSuffix.'-'.mt_rand(100000, 999999);
    //             } while (Complainant::where('application_id', $applicationId)->exists());

    //             $complaint->application_id = $applicationId;
    //         }

    //         /**
    //          * Fetch district info
    //          */
    //         $districtInfo = DB::table('districts')
    //             ->where('id', $request->district)
    //             ->first();

    //         if (! $districtInfo) {
    //             DB::rollBack();

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid district.',
    //             ], 400);
    //         }

    //         /**
    //          * GST Complaint Data Saving
    //          */
    //         if ($type === 'gst') {

    //             $complaint->complaint_type = $type;
    //             $complaint->type_of_complaint = $request->complaintType;
    //             $complaint->gst_description = $request->gstDescription;
    //             $complaint->location = $request->location;
    //             $complaint->pincode = $request->pincode;
    //             $complaint->gst_firm_name = $request->gstFirmName;
    //             $complaint->gst_gstin = strtoupper($request->gstGstin);
    //             $complaint->gst_firm_address = $request->gstFirmAddress;
    //             $complaint->district_id = $request->district;
    //             $complaint->district_name = $districtInfo->name;
    //             $complaint->declaration = 1;

    //             // Handle Multiple File Uploads
    //             if ($request->hasFile('gstProof')) {

    //                 $uploadedFiles = [];
    //                 $files = $request->file('gstProof');
    //                 $files = is_array($files) ? $files : [$files];

    //                 if (count($files) > 5) {
    //                     DB::rollBack();

    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Max 5 files allowed.',
    //                     ], 400);
    //                 }

    //                 foreach ($files as $file) {
    //                     $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    //                     $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
    //                     $uploadedFiles[] = $fileName;
    //                 }

    //                 $complaint->gst_proof = json_encode($uploadedFiles);
    //             }
    //         }

    //         /**
    //          * Mark Step 2 Completed
    //          */
    //         $complaint->is_completed = 1;
    //         $complaint->save();

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Complaint Updated. Your Application ID: '.$complaint->application_id,
    //             'application_id' => $complaint->application_id,
    //         ]);

    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong: '.$e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function submitComplaint(Request $request)
    {
        $files = $request->file('gstProof');

        // Set tax type
        $request->merge([
            'taxType' => 'gst',
        ]);

        // Basic validation for tax type
        $validator = Validator::make($request->all(), [
            'taxType' => 'required|in:gst,vat,excise',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $type = strtolower($request->taxType);
        $mobile = Auth::user()->mobile;

        // GST rules
        $rules = match ($type) {
            'gst' => [
                'complaintType' => [
                    'required',
                    'string',
                    'in:wrong_rate_of_tax,bill/invoice_not_issued,under_reporting,fake_itc,not_obtained_gstin,any_other',
                ],
                'gstDescription' => 'required|string|max:200',
                'location' => 'required|max:150',
                'district' => ['required', 'numeric', 'digits_between:1,2'],
                'pincode' => 'nullable|digits:6',
                'gstProof.*' => 'nullable|mimes:jpg,jpeg,png|max:10240',
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
            ], 422);
        }

        DB::beginTransaction();

        try {

            // STEP A: Check if user already has a COMPLETED complaint
            $completedComplaint = Complainant::where('complainant_phone', $mobile)
                ->where('is_completed', 1)
                ->orderBy('id', 'desc')
                ->first();

            if ($completedComplaint) {

                // Create a NEW complaint auto-filled
                $complaint = new Complainant;
                $complaint->complainant_name = $completedComplaint->complainant_name;
                $complaint->complainant_phone = $completedComplaint->complainant_phone;
                $complaint->complainant_email = $completedComplaint->complainant_email;
                $complaint->current_step = 2;
                $complaint->is_completed = 0;
                $complaint->save(); // Save to generate id

            } else {

                // STEP B: Check if a pending complaint already exists
                $complaint = Complainant::where('complainant_phone', $mobile)
                    ->where('is_completed', 0)
                    ->first();
            }

            // Still no complaint → show error
            if (! $complaint) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'No pending complaint found for update.',
                ], 404);
            }

            // Ensure Step 1 done
            if (! isset($complaint->current_step) || $complaint->current_step <= 1) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Please complete Step 1 first.',
                ]);
            }

            // Create Application ID if not exists
            if (empty($complaint->application_id)) {
                $yearSuffix = now()->format('y');
                $prefix = strtoupper($type);

                do {
                    $applicationId = $prefix.$yearSuffix.'-'.mt_rand(100000, 999999);
                } while (Complainant::where('application_id', $applicationId)->exists());

                $complaint->application_id = $applicationId;
            }

            // Assign details
            $complaint->complaint_type = $type;

            // Validate district
            $districtInfo = DB::table('districts')
                ->where('id', $request->district)
                ->first();

            if (! $districtInfo) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid district.',
                ], 400);
            }

            // GST Case
            if ($type === 'gst') {

                $complaint->type_of_complaint = $request->complaintType ?? '';
                $complaint->gst_description = $request->gstDescription ?? '';
                $complaint->location = $request->location ?? '';
                $complaint->pincode = $request->pincode ?? '';
                $complaint->gst_firm_name = $request->gstFirmName ?? '';
                $complaint->gst_gstin = strtoupper($request->gstGstin) ?? '';
                $complaint->gst_firm_address = $request->gstFirmAddress ?? '';
                $complaint->district_id = $request->district ?? '';
                $complaint->district_name = $districtInfo->name ?? '';
                $complaint->declaration = '1';

                // Upload files
                if ($request->hasFile('gstProof')) {

                    $uploadedFiles = [];
                    $files = $request->file('gstProof');

                    $files = is_array($files) ? $files : [$files];

                    if (count($files) > 5) {
                        DB::rollBack();

                        return response()->json([
                            'success' => false,
                            'message' => 'Max 5 files allowed.',
                        ], 400);
                    }

                    foreach ($files as $file) {
                        $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                        $file->storeAs("complaints/{$complaint->application_id}", $fileName, 'public');
                        $uploadedFiles[] = $fileName;
                    }

                    $complaint->gst_proof = json_encode($uploadedFiles);
                }
            }

            // Mark completed
            $complaint->is_completed = 1;
            $complaint->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Complaint Updated. Your Application ID: '.$complaint->application_id,
                'application_id' => $complaint->application_id,
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: '.$e->getMessage(),
            ], 500);
        }
    }

    // ===========================================================   End ============================================================= //

    public function storeFirstStep(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complaint_type' => 'required|in:vat,gst,excise',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userMobile = trim(Auth::user()->mobile ?? $request->mobile);

        if (! $userMobile) {
            return response()->json([
                'status' => false,
                'message' => 'User mobile not found.',
            ], 400);
        }

        $existingComplaint = Complainant::where('complainant_phone', $userMobile)
            ->where(function ($q) {
                $q->where('is_completed', 0)
                    ->orWhereNull('is_completed');
            })
            ->first();

        if ($existingComplaint) {
            $existingComplaint->complaint_type = $request->complaint_type;
            $existingComplaint->save();

            return response()->json([
                'status' => true,
                'message' => 'Existing complaint updated successfully.',
                'secure_id' => $existingComplaint->secure_id,
                'complaint_id' => $existingComplaint->complaint_id,
            ], 200);
        } else {
            // do {
            //     $complaintId = strtoupper('CMP-' . rand(100000, 999999));
            // } while (Complainant::where('complaint_id', $complaintId)->exists());

            // Here
            $prefix = strtoupper($request->complaint_type);

            do {
                $complaintId = $prefix.'-'.rand(100000, 999999);
            } while (Complainant::where('complaint_id', $complaintId)->exists());

            // Here

            do {
                $secureId = bin2hex(random_bytes(16));
            } while (Complainant::where('secure_id', $secureId)->exists());

            $complaint = Complainant::create([
                'complaint_type' => $request->complaint_type,
                'complainant_phone' => $userMobile,
                'secure_id' => $secureId,
                'complaint_id' => $complaintId,
                'is_completed' => 0,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'New complaint created successfully.',
                'secure_id' => $complaint->secure_id,
                'complaint_id' => $complaint->complaint_id,
            ], 201);
        }
    }

    // Get All Districts - API
    public function getAllDistricts(Request $request)
    {
        $districts = District::select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'status' => true,
            'message' => 'All districts fetched successfully',
            'data' => $districts,
        ], 200);
    }

    // Get All Districts - API

    public function storeSecondStep(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complainant_name' => 'required|string|max:255',
            'complainant_email' => 'required',
            'complainant_aadhaar' => 'required|digits:12',
            'complainant_address' => 'required|string',
            // 'complainant_dist_id'  => 'required',
            'complainant_district' => 'required|string|max:255',
            'upload_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $userMobile = Auth::user()->mobile ?? $request->mobile;
        if (! $userMobile) {
            return response()->json([
                'status' => false,
                'message' => 'User mobile not found',
            ], 400);
        }

        $data['complainant_phone'] = $userMobile;
        $data['is_fraud_related'] = false;

        $complaint = Complainant::where('complainant_phone', $userMobile)
            ->where('is_completed', 0)
            ->first();

        if ($complaint) {
            $complaint->update($data);
        } else {
            do {
                $complaintId = strtoupper('CMP-'.rand(100000, 999999));
            } while (Complainant::where('complaint_id', $complaintId)->exists());

            do {
                $secureId = bin2hex(random_bytes(16));
            } while (Complainant::where('secure_id', $secureId)->exists());

            $data['complaint_id'] = $complaintId;
            $data['secure_id'] = $secureId;
            $data['is_completed'] = false;

            $complaint = Complainant::create($data);
        }

        if ($request->hasFile('upload_document')) {
            $file = $request->file('upload_document');
            $fileName = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('complaints/'.$complaint->complaint_id, $fileName, 'public');

            $complaint->upload_document = $path;
            $complaint->save();
        }

        User::where('mobile', $userMobile)->update([
            'name' => $data['complainant_name'],
            'email' => $data['complainant_email'],
            'aadhaar' => $data['complainant_aadhaar'],
            'address' => $data['complainant_address'],
            // 'district_id' => $data['complainant_dist_id'],
            'district' => $data['complainant_district'] ?? '',
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Step 2 saved successfully',
            'complaint' => $complaint,
        ], 200);
    }

    public function storeThirdStep(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fraud_check' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $secureId = Complainant::where('complainant_phone', Auth::user()->mobile)
            ->where('is_completed', 0)
            ->value('secure_id');

        $complaint = Complainant::where('secure_id', $secureId)->first();

        if (! $complaint) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found.',
            ], 404);
        }

        if ($complaint->is_completed == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint already submitted.',
            ], 400);
        }

        // Update fraud info
        $complaint->update([
            'is_fraud_related' => $data['fraud_check'] == 1 ? true : false,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Step 3 saved successfully',
            'complaint' => $complaint,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firm_name' => 'required|string|max:255',
            'firm_address' => 'required|string|max:500',
            'proof_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:200',
            'remarks' => 'required|string|max:1000',
            'gstin' => 'required|string|max:15',
            'against_district_id' => 'required',
            'estimate_tax_amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $secureId = Complainant::where('complainant_phone', Auth::user()->mobile)
            ->where('is_completed', 0)
            ->value('secure_id');

        $complaint = Complainant::where('secure_id', $secureId)->first();

        if (! $complaint) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found.',
            ], 404);
        }

        if ($complaint->is_completed == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint already submitted.',
            ], 400);
        }

        if ($request->hasFile('proof_document')) {
            if ($complaint->proof_document && Storage::disk('public')->exists($complaint->proof_document)) {
                Storage::disk('public')->delete($complaint->proof_document);
            }

            $file = $request->file('proof_document');
            $fileName = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs(
                'complaints/'.$complaint->complaint_id,
                $fileName,
                'public'
            );

            $data['proof_document'] = $path;
        } else {
            unset($data['proof_document']);
        }

        $data['is_completed'] = 1;
        $data['detc_updated_flag'] = 1;
        $complaint->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Step final saved. Complaint submitted successfully!',
            'complaint_id' => $complaint->complaint_id,
        ], 200);
    }

    public function getComplaintBySecureId(Request $request, $secure_id)
    {
        $complaint = Complainant::where('secure_id', $secure_id)
            ->where('complainant_phone', Auth::user()->mobile)
            ->with(['complainantDistrict:id,name', 'againstDistrict:id,name'])
            ->first();

        if (! $complaint) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found',
            ], 404);
        }

        // Complaint data as array
        $data = $complaint->toArray();

        // Add district names to response
        $data['complainant_district_name'] = optional($complaint->complainantDistrict)->name;
        $data['against_district_name'] = optional($complaint->againstDistrict)->name;

        return response()->json([
            'status' => true,
            'message' => 'Complaint fetched successfully',
            'data' => $data,
        ], 200);
    }

    // Missing
    // public function submitMissingInfoApi(Request $request, $secure_id)
    // {
    //     $complain = Complainant::where('secure_id', $secure_id)->first();

    //     if (!$complain) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Complaint not found'
    //         ], 404);
    //     }

    //     $missingKey = null;

    //     if ($request->has('missing_gst_number')) {

    //         $request->validate([
    //             'missing_gst_number' => 'required|string|max:255'
    //         ]);

    //         $complain->missing_gst_number = $request->missing_gst_number;
    //         $missingKey = 'gst_number';
    //     }

    //     if ($request->has('missing_firm_location')) {

    //         $request->validate([
    //             'missing_firm_location' => 'required|string|max:255'
    //         ]);

    //         $complain->missing_firm_location = $request->missing_firm_location;
    //         $missingKey = 'firm_location';
    //     }

    //     if ($request->has('missing_address')) {

    //         $request->validate([
    //             'missing_address' => 'required|string|max:1000'
    //         ]);

    //         $complain->missing_address = $request->missing_address;
    //         $missingKey = 'address';
    //     }

    //     if (!$missingKey) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No valid missing-info field submitted'
    //         ], 400);
    //     }

    //     $complain->save();

    //     $detcAction = DetcAction::where('user_application_id', $complain->application_id)
    //         ->where('send_to', 'applicant')
    //         ->where('missing_info', $missingKey)
    //         ->latest('id')
    //         ->first();

    //     if ($detcAction) {
    //         $detcAction->returned_to_detc_at = now();
    //         $detcAction->applicant_submitted_at = now();
    //         $detcAction->button_action = 'applicant_submitted';
    //         $detcAction->save();
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Missing information submitted successfully',
    //         'submitted_field' => $missingKey,
    //         'complaint_id' => $complain->application_id
    //     ]);
    // }

    // public function submitMissingInfoApi(Request $request, $secure_id)
    // {
    //     $complain = Complainant::where('secure_id', $secure_id)->first();

    //     if (! $complain) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Complaint not found',
    //         ], 404);
    //     }

    //     $missingKey = null;

    //     if ($request->has('missing_gst_number')) {

    //         $request->validate([
    //             'missing_gst_number' => 'required|string|max:255',
    //         ]);

    //         $complain->missing_gst_number = $request->missing_gst_number;
    //         $missingKey = 'gst_number';
    //     }

    //     if ($request->has('missing_firm_location')) {

    //         $request->validate([
    //             'missing_firm_location' => 'required|string|max:255',
    //         ]);

    //         $complain->missing_firm_location = $request->missing_firm_location;
    //         $missingKey = 'firm_location';
    //     }

    //     if ($request->has('missing_address')) {

    //         $request->validate([
    //             'missing_address' => 'required|string|max:1000',
    //         ]);

    //         $complain->missing_address = $request->missing_address;
    //         $missingKey = 'address';
    //     }

    //     if (! $missingKey) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No valid missing-info field submitted',
    //         ], 400);
    //     }

    //     // -------------------------
    //     // SAVE UPDATED COMPLAINT
    //     // -------------------------
    //     $complain->detc_rise_issue = 0;   // ⬅️ यहाँ required field को reset कर दिया
    //     $complain->save();

    //     // -------------------------
    //     // UPDATE LAST DETC ACTION ENTRY
    //     // -------------------------
    //     $detcAction = DetcAction::where('user_application_id', $complain->application_id)
    //         ->where('send_to', 'applicant')
    //         ->where('missing_info', $missingKey)
    //         ->latest('id')
    //         ->first();

    //     if ($detcAction) {
    //         $detcAction->returned_to_detc_at = now();
    //         $detcAction->applicant_submitted_at = now();
    //         $detcAction->button_action = 'applicant_submitted';
    //         $detcAction->save();
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Missing information submitted successfully',
    //         'submitted_field' => $missingKey,
    //         'complaint_id' => $complain->application_id,
    //     ]);
    // }

    // public function submitMissingInfoApi(Request $request, $secure_id)
    // {
    //     $complain = Complainant::where('secure_id', $secure_id)->first();

    //     if (! $complain) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Complaint not found',
    //         ], 404);
    //     }

    //     $missingKey = null;

    //     // RESET ALL TO NULL IF special flag is sent
    //     if ($request->reset_all == 1) {

    //         $complain->missing_gst_number = null;
    //         $complain->missing_firm_location = null;
    //         $complain->missing_address = null;
    //         $complain->save();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'All missing info fields reset successfully',
    //         ]);
    //     }

    //     // NOW UPDATE FIELDS
    //     if ($request->has('missing_gst_number')) {
    //         $request->validate(['missing_gst_number' => 'required|string|max:255']);
    //         $complain->missing_gst_number = $request->missing_gst_number;
    //         $missingKey = 'gst_number';
    //     }

    //     if ($request->has('missing_firm_location')) {
    //         $request->validate(['missing_firm_location' => 'required|string|max:255']);
    //         $complain->missing_firm_location = $request->missing_firm_location;
    //         $missingKey = 'firm_location';
    //     }

    //     if ($request->has('missing_address')) {
    //         $request->validate(['missing_address' => 'required|string|max:1000']);
    //         $complain->missing_address = $request->missing_address;
    //         $missingKey = 'address';
    //     }

    //     if (! $missingKey) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No valid missing-info field submitted',
    //         ], 400);
    //     }

    //     $complain->save();

    //     // UPDATE detc action record
    //     $detcAction = DetcAction::where('user_application_id', $complain->application_id)
    //         ->where('send_to', 'applicant')
    //         ->where('missing_info', $missingKey)
    //         ->latest('id')
    //         ->first();

    //     if ($detcAction) {
    //         $detcAction->returned_to_detc_at = now();
    //         $detcAction->applicant_submitted_at = now();
    //         $detcAction->button_action = 'applicant_submitted';
    //         $detcAction->save();
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Missing information submitted successfully',
    //         'submitted_field' => $missingKey,
    //         'complaint_id' => $complain->application_id,
    //     ]);
    // }

    public function submitMissingInfoApi(Request $request, $secure_id)
    {
        $complain = Complainant::where('secure_id', $secure_id)->first();

        // dd($complain);

        if (! $complain) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found',
            ], 404);
        }

        $missingKey = null;

        // GST
        if ($request->has('missing_gst_number')) {
            $request->validate([
                'missing_gst_number' => 'required|string|max:255',
            ]);
            $complain->missing_gst_number = $request->missing_gst_number;
            $missingKey = 'gst_number';
        }

        // FIRM LOCATION
        if ($request->has('missing_firm_location')) {
            $request->validate([
                'missing_firm_location' => 'required|string|max:255',
            ]);
            $complain->missing_firm_location = $request->missing_firm_location;
            $missingKey = 'firm_location';
        }

        // ADDRESS
        if ($request->has('missing_address')) {
            $request->validate([
                'missing_address' => 'required|string|max:1000',
            ]);
            $complain->missing_address = $request->missing_address;
            $missingKey = 'address';
        }

        if (! $missingKey) {
            return response()->json([
                'status' => false,
                'message' => 'No valid missing-info field submitted',
            ], 400);
        }

        // 🟩 RESET DETC ISSUE FLAGS
        $complain->detc_rise_issue = 0;
        $complain->detc_issue = null;

        $complain->save();

        // Update DETC Action
        $detcAction = DetcAction::where('user_application_id', $complain->application_id)
            // ->where('send_to', 'applicant')
            ->where('missing_info', $missingKey)
            ->latest('id')
            ->first();

        // dd($detcAction);

        // dd('Done');

        if ($detcAction) {
            $detcAction->returned_to_detc_at = now();
            $detcAction->applicant_submitted_at = now();
            $detcAction->button_action = 'applicant_submitted';
            $detcAction->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Missing information submitted successfully',
            'submitted_field' => $missingKey,
            'complaint_id' => $complain->application_id,
        ]);
    }
}
