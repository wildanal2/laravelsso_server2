<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsoUserHasEntity extends Model
{
    use HasFactory;

    protected $table = 'sso_user_has_entities'; // Sesuaikan dengan nama tabel yang sesuai
    protected $fillable = ['user_id', 'entity_id'];
    public $timestamps = true;
    
    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan model SsoEntity
    public function entity()
    {
        return $this->belongsTo(SsoEntity::class, 'entity_id');
    }
}
