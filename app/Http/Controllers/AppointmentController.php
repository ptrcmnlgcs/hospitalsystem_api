<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctorID = $request->input('doctorID');
        $patientID = $request->input('patientID');

        $query = Appointment::join('users as patients', 'appointments.patientID', '=', 'patients.id')
            ->join('users as doctors', 'appointments.doctorID', '=', 'doctors.id')
            ->select('appointments.*', 'patients.name as PatientName', 'doctors.name as DoctorName');

        if ($doctorID) {
            $query->where('appointments.doctorID', $doctorID);
        }

        if ($patientID) {
            $query->where('appointments.patientID', $patientID);
        }

        $appointments = $query->get();

        if ($appointments->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'Appointments' => $appointments
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
            'time' => 'required',
            'patientID' => 'required',
            'doctorID' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ], 400);
        }

        try {
            Appointment::create($request->all());
            return response()->json([
                'status' => 200,
                'message' => "Appointment Created Successfully"
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
        $appointment = Appointment::find($id);

        return response()->json([
            'status' => $appointment ? 200 : 404,
            'appointment' => $appointment ?? 'No appointment found.'
        ], $appointment ? 200 : 404);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required',
            'patientID' => 'required',
            'doctorID' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ], 400);
        }

        try {
            $appointment = Appointment::find($id);
            if (!$appointment) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No appointment found.'
                ], 404);
            }

            $appointment->update($request->all());
            return response()->json([
                'status' => 200,
                'message' => "Appointment updated Successfully"
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
            $appointment = Appointment::find($id);
            if (!$appointment) {
                return response()->json([
                    'status' => 404,
                    'message' => "No valid appointment to delete."
                ], 404);
            }

            $appointment->delete();
            return response()->json([
                'status' => 200,
                'message' => "Appointment deleted successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong: " . $e->getMessage()
            ], 500);
        }
    }
}
