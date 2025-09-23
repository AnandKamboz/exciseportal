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
        return view('complainant.create');
    }

    public function storeFirstStep(Request $request)
    {
        $data = $request->validate([
            'complainant_name' => 'required|string|max:255',
            // 'complaint_type'   => 'required',
            'mobile'           => 'required|numeric|digits:10',
            'email'            => 'required|email|unique:users,email,' . Auth::id(),
            'aadhaar'          => 'required|digits:12',
            'address'          => 'required|string',
            'upload_document'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $userMobile = Auth::user()->mobile;
        $data['mobile'] = $userMobile;

        $complaint = Complainant::where('mobile', $userMobile)->first();

        if ($complaint && $complaint->is_completed) {
            return response()->json([
                'message' => 'Complaint already submitted for this mobile number.'
            ], 403);
        }

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
}
