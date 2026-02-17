<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_code',
        'name',
        'phone',
        'membership_type',
        'join_date',
        'fee',
        'fee_method',
        'comment',
        'status',
        'gender',
        'last_fee_date',
        'next_fee_due',
        'FeeSubmitDate',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            // Generate unique code (e.g. MEM001)
            $latestId = Member::max('id') + 1;
            $member->member_code = 'MEM' . str_pad($latestId, 3, '0', STR_PAD_LEFT);
        });
    }
}
