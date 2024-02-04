<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SsoEntity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sso_entities'; // Nama tabel yang sesuai
    protected $fillable = ['company_reg', 'name', 'description', 'logo'];
    public $timestamps = true;

    
}
