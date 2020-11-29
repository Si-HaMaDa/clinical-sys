<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'birth' => $this->birth,
            'address' => $this->address,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'date' => $this->date,
            'time' => $this->time,
            'payment_method' => $this->payment_method,
            'offer_id' => $this->offer_id,
            'tax_id' => $this->tax_id,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
