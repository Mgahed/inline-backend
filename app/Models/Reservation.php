<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'reservation_number',
        'user_id',
        'service_id',
        'branch_service_id'
    ];

    public function user()
    {
        $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function service()
    {
        $this->belongsTo(services::class,'service_id','id');
    }

    public function branch_service()
    {
        $this->belongsTo(BranchService::class,'service_id','id');
    }
}
