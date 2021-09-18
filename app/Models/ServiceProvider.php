<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;

    public function branches()
    {
        return $this->hasMany(Branches::class, 'service_provider_id', 'id');
    }
}
