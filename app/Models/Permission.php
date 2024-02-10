<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{

    public function feature()
    {
        return $this->hasMany(SsoModuleFeature::class, 'permission_id', 'id');
    }
}
