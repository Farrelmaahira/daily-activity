<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeResource extends JsonResource
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
            'overtime' => $this->overtime,
            'date' => $this->date->isoFormat('DD MMMM Y'),
            'from' => $this->from,
            'untill' => $this->untill,
            'user' => $this->user->name,
            'position' => $this->user->position_id
        ];
    }
}
