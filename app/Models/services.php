<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'cost'
    ];

    public function branches()
    {
        $this->belongsToMany(Branches::class, 'branches_services');
    }

}
