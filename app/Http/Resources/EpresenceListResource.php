<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EpresenceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $epresence = $this;

        $first = $epresence->first();
        $user = $first?->user;

        $in = $epresence->firstWhere('type', 'in');
        $out = $epresence->firstWhere('type', 'out');

        return [
            'id_user'       => $user?->id,
            'nama_user'     => $user?->nama,
            'tanggal'       => $first ? Carbon::parse($first->waktu)->format('Y-m-d') : '-',
            'waktu_masuk'   => $in ? Carbon::parse($in->waktu)->format('H:i:s') : '-',
            'waktu_pulang'  => $out ? Carbon::parse($out->waktu)->format('H:i:s') : '-',
            'status_masuk'  => $in ? ($in->is_approve ? 'APPROVE' : 'REJECT') : '-',
            'status_pulang' => $out ? ($out->is_approve ? 'APPROVE' : 'REJECT') : '-',
        ];
    }

}
