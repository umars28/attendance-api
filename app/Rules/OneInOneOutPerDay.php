<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Log;

class OneInOneOutPerDay implements Rule
{
    protected $userId;
    protected $type;

    protected $time;

    public function __construct($userId, $type, $time)
    {
        $this->userId = $userId;
        $this->type = strtolower($type);
        try {
            $this->time = $time ? Carbon::createFromFormat('Y-m-d H:i:s', $time) : null;
        } catch (\Exception $e) {
            $this->time = null;
        }    
    }

    public function passes($attribute, $value)
    {
        if (!$this->time) {
            return true;
        }
    
        $exists = DB::table('epresence')
            ->where('id_users', $this->userId)
            ->where('type', $this->type)
            ->whereDate('waktu', $this->time)
            ->exists();

        return !$exists;
    }

    public function message()
    {
        $typeName = match(strtoupper($this->type)) {
            'IN' => 'absensi masuk',
            'OUT' => 'absensi pulang',
            default => 'absensi',
        };
    
        return "You have already performed {$typeName} on the selected date.";
    }
}
