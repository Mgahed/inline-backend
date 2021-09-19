<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{

    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'service_provider_id',
        'name',
        'email',
        'address',
        'phone_number',
        'start_time',
        'close_time'
    ];


    public function serviceprovider()
    {
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id', 'id');
    }

    public function services()
    {
        return $this->belongsToMany(services::class, 'branches_services');
    }
}
