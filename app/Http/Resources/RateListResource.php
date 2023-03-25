<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateListResource extends JsonResource
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
            "id" => $this['id'],
            "project" => $this->project->name,
            "city" => $this->city->name,
            "desc" => $this['desc'],
            "division" => $this['division'],
            "rateListDetail" => $this->rateListDetails,
            "updated_at" => $this['updated_at'],
            "created_at" => $this['created_at'],
        ];
    }
}
