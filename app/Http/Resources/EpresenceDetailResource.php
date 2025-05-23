<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EpresenceDetailResource extends JsonResource
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
            'id'            => $this->id,
            'type'          => strtoupper($this->type),
            'waktu'         => $this->waktu ? $this->waktu->format('Y-m-d H:i:s') : null,
            'is_approve'    => $this->is_approve ? 'APPROVE' : 'REJECT'
        ];
    }
}
