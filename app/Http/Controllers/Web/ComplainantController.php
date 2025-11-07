<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Complainant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ComplainantController extends Controller
{
    public function complainant()
    {
        if (!Auth::check() || !$request->session()->has('mobile')) {
            return redirect('/')->with('error', 'Unauthorized access!');
        }

        $districts = DB::table('districts')->get();
        $userMobile = Auth::user()->mobile;
        $userData = Complainant::where('complainant_phone', $userMobile)->where('is_completed',0)->first();
        return view('complainant.create',compact('userMobile','userData','districts'));
    }

    public function storeFirstStep(Request $request)
    {
        $request->validate([
            'complaint_type' => 'required|in:vat,gst,excise',
        ]);

        $userMobile = trim(Auth::user()->mobile);


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
                'status' => 'updated',
                'message' => 'Existing complaint updated successfully.',
                'secure_id' => $existingComplaint->secure_id,
                'complaint_id' => $existingComplaint->complaint_id,
            ]);
        } else {
            $prefix = strtoupper($request->complaint_type);

            do {
                $complaintId = $prefix . '-' . rand(100000, 999999);
            } while (Complainant::where('complaint_id', $complaintId)->exists());

            do {
                $secureId = bin2hex(random_bytes(16));
            } while (Complainant::where('secure_id', $secureId)->exists());

            $complaint = new Complainant();
            $complaint->complaint_type = $request->complaint_type;
            $complaint->complainant_phone = $userMobile;
            $complaint->secure_id = $secureId;
            $complaint->complaint_id = $complaintId;
            $complaint->is_completed = 0;
            $complaint->save();

            return response()->json([
                'status' => 'created',
                'message' => 'New complaint created successfully.',
                'secure_id' => $complaint->secure_id,
                'complaint_id' => $complaint->complaint_id,
            ]);
        }
    }

    public function storeSecondStep(Request $request)
    {
        $data = $request->validate([
            'complainant_name' => 'required|string|max:255',
            'complainant_phone'           => 'required|numeric|digits:10',
            'complainant_email'            => 'required|email|unique:users,email,' . Auth::id(),
            'complainant_aadhaar'          => 'required|digits:12',
            'complainant_address'          => 'required|string',
            'pin_code'                => 'required|digits:6',
            'complainant_state'       => 'required|string|max:255',
            'complainant_district'       => 'required|string|max:255',
            'bank_account'            => 'required|numeric|digits_between:8,16',
            'confirm_bank_account'    => 'required|same:bank_account',
            'bank_name'               => 'required|string|max:255',
            'ifsc_code'               => 'required|string|max:20',
            'bank_branch_address'     => 'required|string|max:500',
        ]);

        $userMobile = Auth::user()->mobile;
        $data['complainant_phone'] = $userMobile;
        $data['is_fraud_related'] = false;

        $complaint = Complainant::where('complainant_phone', $userMobile)->where('is_completed',0)->first();

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
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('complaints/' . $complaint->complaint_id, $fileName, 'public');

            $complaint->upload_document = $path;
            $complaint->save();
        }

        User::where('mobile', $userMobile)
            ->update([
                'name'       => $data['complainant_name'],
                'email'      => $data['complainant_email'],
                'aadhaar'    => $data['complainant_aadhaar'],
                'address'    => $data['complainant_address'],
                'district'=> $data['complainant_district'],
                'updated_at' => now(),
            ]);

        return response()->json([
            'message'   => 'Step 2 saved',
            'complaint' => $complaint
        ]);
    }

    public function storeThirdStep(Request $request)
    {
        $data = $request->validate([
            'fraud_check' => 'required|in:1,0',
        ]);

        $secureId = Complainant::where('complainant_phone', auth::user()->mobile)->where('is_completed',0)->value('secure_id');
        $complaint = Complainant::where('secure_id', $secureId)->first();

        if (!$complaint) {
            return redirect()->back()->with('error', 'Complaint not found.');
        }

        if ($complaint->is_completed == '1') {
            return redirect()->back()->with('error', 'Complaint already submitted.');
        }

        $complaint->update([
            'is_fraud_related' => $data['fraud_check'] == 1 ? true : false,
        ]);

        return response()->json([
            'message'   => 'Step 3 saved',
            'complaint' => $complaint
        ]);
    }

    public function store(Request $request)
    {
            $data = $request->validate([
                'firm_name'      => 'required|string|max:255',
                'firm_address'    => 'required|string|max:500',
                'proof_document'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:200',
                'remarks'         => 'required|string|max:1000',
                'gstin'           => 'required|string|max:15',
                'against_district_id' => 'required',
                'estimate_tax_amount' => 'required|numeric|min:1',
            ]);

            $secureId = Complainant::where('complainant_phone', auth()->user()->mobile)
                        ->where('is_completed', 0)
                        ->value('secure_id');

            $complaint = Complainant::where('secure_id', $secureId)->first();

            if (!$complaint) {
                return redirect()->back()->with('error', 'Complaint not found.');
            }


            if ($complaint->is_completed == '1') {
                return redirect()->back()->with('error', 'Complaint already submitted.');
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

            // $request->session()->flush();

           return response()->json([
                'success'       => true,
                'message'       => 'Step final saved. Complaint submitted successfully!',
                'complaint_id'  => $complaint->complaint_id,
            ]);

    }

    public function getUserData(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $complaints = Complainant::where('complainant_phone', auth()->user()->mobile)
                          ->where('is_completed', 0)
                          ->first();


        return response()->json([
            'status' => 'success',
            'complaints' => $complaints
        ]);
    }


}
