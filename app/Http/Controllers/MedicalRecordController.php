<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Service;
use App\Models\Medication;

class MedicalRecordController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'userID' => 'required|string',
            'organizationID' => 'required|string',
            'result' => 'required|string',
            'namePtient' => 'required|string',
            'cccdPatient' => 'required|string',
            'birthdayPatient' => 'required|date',
            'sexPatient' => 'required|string',
            'services' => 'required|array',
            'medications' => 'required|array',
        ]);

        $medicalRecord = MedicalRecord::create($request->all());

        foreach ($request->services as $serviceData) {
            $service = Service::create([
                'name' => $serviceData['name'],
                'desc' => $serviceData['desc'],
                'medical_record_id' => $medicalRecord->id,
            ]);

            foreach ($serviceData['details'] as $detailData) {
                $service->details()->create($detailData);
            }
        }

        foreach ($request->medications as $medicationData) {
            Medication::create([
                'name' => $medicationData['name'],
                'quantity' => $medicationData['quantity'],
                'instructionsForUse' => $medicationData['instructionsForUse'],
                'desc' => $medicationData['desc'],
                'medical_record_id' => $medicalRecord->id,
            ]);
        }

        return response()->json($medicalRecord, 201);
    }

    public function addServices(Request $request, $id)
    {
        $request->validate([
            'services' => 'required|array',
        ]);

        $medicalRecord = MedicalRecord::findOrFail($id);

        foreach ($request->services as $serviceData) {
            $service = Service::create([
                'name' => $serviceData['name'],
                'desc' => $serviceData['desc'],
                'medical_record_id' => $medicalRecord->id,
            ]);

            foreach ($serviceData['details'] as $detailData) {
                $service->details()->create($detailData);
            }
        }

        return response()->json($medicalRecord->load('services.details'), 200);
    }

    public function addServiceDetails(Request $request, $id, $serviceId)
    {
        $request->validate([
            'details' => 'required|array',
        ]);

        $service = Service::where('id', $serviceId)->where('medical_record_id', $id)->firstOrFail();

        foreach ($request->details as $detailData) {
            $service->details()->create($detailData);
        }

        return response()->json($service->load('details'), 200);
    }

    public function viewAllRecords(Request $request)
    {
        $request->validate([
            'cccdPatient' => 'required|string',
        ]);

        $records = MedicalRecord::where('cccdPatient', $request->cccdPatient)->get();

        foreach ($records as $record) {
            $record->services = $record->services()->with('details')->get();
            $record->medications = $record->medications;
        }

        return response()->json($records, 200);
    }

    public function viewRecordDetails($id)
    {
        $record = MedicalRecord::with(['services.details', 'medications'])->findOrFail($id);

        return response()->json($record, 200);
    }
}
