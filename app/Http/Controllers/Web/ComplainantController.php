<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Complainant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ComplainantController extends Controller
{
    public function complainant()
    {
        if (!Auth::check()) {
          return redirect('/');
        }
        $userMobile = Auth::user()->mobile;
        $userData = Complainant::where('mobile', $userMobile)->where('is_completed',0)->first();
        return view('complainant.create',compact('userMobile','userData'));
    }

    public function storeFirstStep(Request $request)
    {
        $data = $request->validate([
            'complainant_name' => 'required|string|max:255',
            'complaint_type' => 'required|in:vat,gst,excise',
            'mobile'           => 'required|numeric|digits:10',
            'email'            => 'required|email|unique:users,email,' . Auth::id(),
            'aadhaar'          => 'required|digits:12',
            'address'          => 'required|string',
            // 'upload_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:200',
        ]);

        $userMobile = Auth::user()->mobile;
        $data['mobile'] = $userMobile;
        $data['is_fraud_related'] = false;

        $complaint = Complainant::where('mobile', $userMobile)->where('is_completed',0)->first();
        
        // if ($complaint && $complaint->is_completed) {
        //     return response()->json([
        //         'message' => 'Complaint already submitted for this mobile number.'
        //     ], 403);
        // }

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
                'email'      => $data['email'],
                'aadhaar'    => $data['aadhaar'],
                'address'    => $data['address'],
                'updated_at' => now(),
            ]);

        return response()->json([
            'message'   => 'Step 1 saved',
            'complaint' => $complaint
        ]);
    }

    public function storeSecondStep(Request $request)
    {
        $data = $request->validate([
            'fraud_check' => 'required|in:1,0',
        ]);
        
        $secureId = Complainant::where('mobile', auth::user()->mobile)->where('is_completed',0)->value('secure_id');
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
            'message'   => 'Step 2 saved',
            'complaint' => $complaint
        ]);
    }

    public function store(Request $request)
    {       
            $data = $request->validate([
                'firm_address'    => 'required|string|max:500',
                'proof_document'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:200',
                'remarks'         => 'required|string|max:1000',
                'gstin'           => 'required|string|max:15',
            ]);
          
            $secureId = Complainant::where('mobile', auth()->user()->mobile)
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
            $complaint->update($data);
            
            // $request->session()->flush();

           return response()->json([
                'success'       => true,
                'message'       => 'Step 3 saved. Complaint submitted successfully!',
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

        $complaints = Complainant::where('mobile', auth()->user()->mobile)
                          ->where('is_completed', 0)
                          ->first();


        return response()->json([
            'status' => 'success',
            'complaints' => $complaints
        ]);
    }


}
