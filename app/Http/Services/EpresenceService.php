<?php

namespace App\Http\Services;

use App\Models\Epresence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;

class EpresenceService
{
    public function getList(User $user, $limit = 10, $page = 1)
    {
        if ($user->subordinates()->exists()) {
            $subordinateIds = $user->subordinates()->pluck('id');
            $data = Epresence::whereIn('id_users', $subordinateIds)
                ->with('user')
                ->get();
        } else {
            $data = Epresence::where('id_users', $user->id)
                ->with('user')
                ->get();
        }

        $grouped = $data->groupBy(function ($item) {
            return $item->id_users . '_' . Carbon::parse($item->waktu)->format('Y-m-d');
        });

        $sorted = $grouped->sortByDesc(function ($group) {
            return Carbon::parse($group->first()->waktu)->format('Y-m-d');
        })->values();

        $total = $sorted->count();
        $results = $sorted->slice(($page - 1) * $limit, $limit)->values();
    
        return new LengthAwarePaginator(
            $results,
            $total,
            $limit,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
