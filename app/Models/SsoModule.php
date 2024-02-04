<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsoModule extends Model
{
    use HasFactory;
    
    protected $table = 'sso_modules';
    protected $fillable = ['name', 'codename', 'description', 'last_version'];
    public $timestamps = true;
}
