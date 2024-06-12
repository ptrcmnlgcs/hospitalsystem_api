<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalRecordController extends Controller
{

    public function index(Request $request)
    {
        $patientID = $request->input('patientID');
        $query = MedicalRecord::leftJoin('users as patients', 'medical_records.patientID', '=', 'patients.id')
            ->select('medical_records.*', 'patients.name as PatientName');

        if ($patientID) {
            $query->where('medical_records.patientID', $patientID);
        }

        $medicalRecords = $query->get();

        return response()->json([
            'status' => $medicalRecords->isNotEmpty() ? 200 : 404,
            'MedicalRecords' => $medicalRecords,
            'Message' => $medicalRecords->isEmpty() ? 'No Records Found' : null
        ], $medicalRecords->isNotEmpty() ? 200 : 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patientID' => 'required',
            'RecordDetails' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->messages()], 400);
        }

        try {
            MedicalRecord::create($request->only(['patientID', 'RecordDetails']));

            return response()->json(['status' => 200, 'message' => 'Medical Record Created Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }


    public function show(string $id)
    {
        $medicalRecord = MedicalRecord::find($id);

        return response()->json([
            'status' => $medicalRecord ? 200 : 404,
            'medicalRecord' => $medicalRecord,
            'message' => $medicalRecord ? null : 'No record found.'
        ], $medicalRecord ? 200 : 404);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'patientID' => 'required',
            'RecordDetails' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->messages()], 400);
        }

        try {
            $medicalRecord = MedicalRecord::find($id);

            if (!$medicalRecord) {
                return response()->json(['status' => 404, 'message' => 'Medical Record not found.'], 404);
            }

            $medicalRecord->update($request->only(['patientID', 'RecordDetails']));

            return response()->json(['status' => 200, 'message' => 'Medical Record updated Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $medicalRecord = MedicalRecord::find($id);

            if (!$medicalRecord) {
                return response()->json(['status' => 404, 'message' => 'No valid medical record to delete.'], 404);
            }

            $medicalRecord->delete();

            return response()->json(['status' => 200, 'message' => 'Medical Record deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}
