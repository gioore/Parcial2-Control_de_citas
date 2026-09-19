<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(private readonly AppointmentService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return AppointmentResource::collection($this->service->list($request->validate([
            'doctor_id' => ['sometimes', 'integer', 'exists:doctors,id'],
            'patient_id' => ['sometimes', 'integer', 'exists:patients,id'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
        ])));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        return (new AppointmentResource($this->service->create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment->load(['patient', 'doctor']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        return new AppointmentResource($this->service->update($appointment, $request->validated()));
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment)
    {
        return new AppointmentResource($this->service->changeStatus($appointment, $request->validated('status')));
    }

    public function destroy(Appointment $appointment)
    {
        return new AppointmentResource($this->service->changeStatus($appointment, 'cancelled'));
    }
}
