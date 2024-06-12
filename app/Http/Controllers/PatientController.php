<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{

    public function index()
    {
        $patients = User::where('userType', 'patient')->get();
        if ($patients->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'PatientAccounts' => $patients
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'Message' => 'No Records Found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ], 400);
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'userType' => 'patient'
            ]);

            return response()->json([
                'status' => 200,
                'message' => "Account Created Successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong: " . $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $user = User::find($id);

        if ($user) {
            if ($user->userType === 'patient') {
                return response()->json([
                    'status' => 200,
                    'patient' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The user with ID ' . $id . ' is not a patient.'
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No user found with ID ' . $id
            ], 404);
        }
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'userType' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ], 400);
        }

        try {
            $account = User::find($id);

            if (!$account) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found.'
                ], 404);
            }

            $account->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'userType' => $request->userType
            ]);

            return response()->json([
                'status' => 200,
                'message' => "Account updated Successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong: " . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => "No valid account to delete."
                ], 404);
            }

            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => "Account deleted successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong: " . $e->getMessage()
            ], 500);
        }
    }
}