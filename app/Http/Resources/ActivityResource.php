<?php

namespace App\Http\Resources;

use App\Models\DailyActivity;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

            return [
                'no' => $this->no,
                'id' => $this->id,
                'user' => $this->user->name,
                'position' =>  $this->user->position_id,
                'date' => $this->date->isoFormat('DD MMMM Y'),
                'activity' => $this->activity,
            ];
    }
}
//