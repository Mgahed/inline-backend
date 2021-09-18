<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{

    protected $fillable = [
        'name',
        'cost'
    ];

    public function branches()
    {
        return $this->belongsToMany(Branches::class);
    }

    use HasFactory;
}
