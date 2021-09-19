<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchService extends Model
{
    use HasFactory;

    protected $table = "branches_services";

    protected $fillable = [
        'branches_id',
        'services_id'
    ];
}
