<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsoUserHasModule extends Model
{
    use HasFactory;
    protected $table = 'sso_user_has_modules';
    protected $fillable = ['user_id', 'module_id'];
    public $timestamps = true;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(SsoModule::class);
    }
}
