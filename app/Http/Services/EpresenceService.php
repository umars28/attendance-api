<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatus;
use App\Models\Epresence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Log;

class EpresenceService
{
    protected GateContract $gate;

    public function __construct(GateContract $gate)
    {
        $this->gate = $gate;
    }

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

    public function store(array $data, User $user): Epresence
    {
        $this->gate->authorize('create', Epresence::class);

        $data['type'] = strtolower($data['type']);

        return Epresence::create([
            'id_users'      => $user->id,
            'type'          => $data['type'],
            'waktu'         => $data['waktu'],
            'is_approve'    => false
        ]);
    }

    public function approve(int $id)
    {
        $epresence = Epresence::with('user')->findOrFail($id);
        
        $this->gate->authorize('approve', $epresence);

        $epresence->update(['is_approve' => ApprovalStatus::True]);

        return $epresence;
    }
}
