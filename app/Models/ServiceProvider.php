<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone_number',
        'type'
    ];

    public function branches()
    {
        return $this->hasMany(Branches::class, 'service_provider_id', 'id');
    }
}
