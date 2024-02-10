<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsoModuleFeature extends Model
{
    use HasFactory;

    protected $table = 'sso_module_features';
    protected $fillable = ['module_id', 'permission_id', 'name'];
    public $timestamps = true;

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }

    public function module()
    {
        return $this->hasOne(SsoModule::class, 'id', 'module_id');
    }
}
