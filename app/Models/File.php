<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    // In the File model
    protected $fillable = ['name', 'description', 'google_drive_id', 'parents'];

}
