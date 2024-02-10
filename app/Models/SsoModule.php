<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsoModule extends Model
{
    use HasFactory;

    protected $table = 'sso_modules';
    protected $fillable = ['name', 'codename', 'description', 'last_version', 'url', 'oclient_id'];
    public $timestamps = true;

    public function oClient()
    {
        return $this->hasOne(OAuthClient::class, 'id', 'oclient_id');
    }
}
