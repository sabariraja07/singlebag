<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class ShopUser extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'shop_id',
        'user_id',
        'role_id',
        'status',   
    ];

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
