<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = ['member_id', 'date'];
        public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
