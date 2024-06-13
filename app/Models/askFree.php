<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class askFree extends Model
{
    use HasFactory;

    protected $table="free";

    protected $fillable=[
        "startDate",
        "endDate",
        "startTime",
        "endTime",
        "state",
        "userid",
        "reason",
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'userid','id');
    }
}
