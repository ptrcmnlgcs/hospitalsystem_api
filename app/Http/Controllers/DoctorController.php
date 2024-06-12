<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{

    public function index()
    {
        $doctors = User::where('userType', 'doctor')->get();
        if ($doctors->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'DoctorAccounts' => $doctors
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
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email',
            'password' => 'required|string|min:8|max:191'
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
                'userType' => 'doctor'
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
            if ($user->userType === 'doctor') {
                return response()->json([
                    'status' => 200,
                    'doctor' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'The user with ID ' . $id . ' is not a doctor.'
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
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'password' => 'string|min:8|max:191',
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

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'userType' => $request->userType
            ];

            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            $account->update($updateData);

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
