<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{
    // public static $wrap = '';


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'department' => $this->department,
            'project_name' => $this->project_name,
            'purpose' => $this->purpose,
            'departure_destination' => $this->departure_destination,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'start_visa' => $this->start_visa,
            'is_active' => $this->is_active,
            'requirements' => $this->requirements,
            'pocket_money' => $this->pocket_money,

            'traveler' => $this->traveler,
            'reportingTo' => $this->reportingTo,
            'approval' => $this->approval,
            'client' => $this->client,
            'departure' => $this->departure,
            'destination' => $this->destination,


            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        return $response;
    }
}
