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
use Illuminate\Support\Facades\Validator;
use App\Models\District;



class ComplainantController extends Controller
{
    public function storeFirstStep(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complaint_type' => 'required|in:vat,gst,excise',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()  
            ], 422);
        }

        $userMobile = trim(Auth::user()->mobile ?? $request->mobile);

        if (!$userMobile) {
            return response()->json([
                'status' => false,
                'message' => 'User mobile not found.',
            ], 400);
        }

        $existingComplaint = Complainant::where('complainant_phone', $userMobile)
            ->where(function($q) {
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
            do {
                $complaintId = strtoupper('CMP-' . rand(100000, 999999));
            } while (Complainant::where('complaint_id', $complaintId)->exists());

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
            'data' => $districts
        ], 200);
    }

    // Get All Districts - API

   public function storeSecondStep(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'complainant_name'     => 'required|string|max:255',
            'complainant_email'    => 'required',
            'complainant_aadhaar'  => 'required|digits:12',
            'complainant_address'  => 'required|string',
            'complainant_dist_id'  => 'required',
            'upload_document'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

   
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $userMobile = Auth::user()->mobile ?? $request->mobile;
        if (!$userMobile) {
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
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('complaints/' . $complaint->complaint_id, $fileName, 'public');

            $complaint->upload_document = $path;
            $complaint->save();
        }

        User::where('mobile', $userMobile)->update([
            'name'        => $data['complainant_name'],
            'email'       => $data['complainant_email'],
            'aadhaar'     => $data['complainant_aadhaar'],
            'address'     => $data['complainant_address'],
            'district_id' => $data['complainant_dist_id'],
            'updated_at'  => now(),
        ]);

        return response()->json([
            'status'    => true,
            'message'   => 'Step 2 saved successfully',
            'complaint' => $complaint
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
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $secureId = Complainant::where('complainant_phone', Auth::user()->mobile)
                                ->where('is_completed', 0)
                                ->value('secure_id');

        $complaint = Complainant::where('secure_id', $secureId)->first();

        if (!$complaint) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found.'
            ], 404);
        }

        if ($complaint->is_completed == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint already submitted.'
            ], 400);
        }

        // Update fraud info
        $complaint->update([
            'is_fraud_related' => $data['fraud_check'] == 1 ? true : false,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Step 3 saved successfully',
            'complaint' => $complaint
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firm_name'           => 'required|string|max:255',
            'firm_address'        => 'required|string|max:500',
            'proof_document'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:200',
            'remarks'             => 'required|string|max:1000',
            'gstin'               => 'required|string|max:15',
            'against_district_id' => 'required',
            'estimate_tax_amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $secureId = Complainant::where('complainant_phone', Auth::user()->mobile)
                                ->where('is_completed', 0)
                                ->value('secure_id');

        $complaint = Complainant::where('secure_id', $secureId)->first();

        if (!$complaint) {
            return response()->json([
                'status'  => false,
                'message' => 'Complaint not found.'
            ], 404);
        }

        if ($complaint->is_completed == 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Complaint already submitted.'
            ], 400);
        }

        if ($request->hasFile('proof_document')) {
            if ($complaint->proof_document && Storage::disk('public')->exists($complaint->proof_document)) {
                Storage::disk('public')->delete($complaint->proof_document);
            }

            $file = $request->file('proof_document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs(
                'complaints/' . $complaint->complaint_id,
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
            'status'       => true,
            'message'      => 'Step final saved. Complaint submitted successfully!',
            'complaint_id' => $complaint->complaint_id,
        ], 200);
    }

    // public function getComplaintBySecureId(Request $request, $secure_id)
    // { 
    //     $complaint = Complainant::where('secure_id', $secure_id)
    //         ->where('complainant_phone', auth::user()->mobile)
    //         ->first();

    //     if (!$complaint) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Complaint not found'
    //         ], 404);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Complaint fetched successfully',
    //         'data' => $complaint
    //     ], 200);
    // }

    public function getComplaintBySecureId(Request $request, $secure_id)
    {
        $complaint = Complainant::where('secure_id', $secure_id)
            ->where('complainant_phone', Auth::user()->mobile)
            ->with(['complainantDistrict:id,name', 'againstDistrict:id,name'])
            ->first();

        if (!$complaint) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found'
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
            'data' => $data
        ], 200);
    }

}
