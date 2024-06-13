<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class urenRegister extends Model
{
    use HasFactory;
    protected $table="uren";
    protected $fillable=[
        'userId',
        'startTime',
        'endTime',
        'pause',
        'day',
        'state',
        'reason',
        'category'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,"userid","id");
    }

    public function cat()
    {
        return $this->hasOne(category::class,"id","category");
    }
}
