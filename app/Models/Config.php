<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = ['path']; // Define fillable fields

    public static function getConfigData()
    {
        return self::first(); // Retrieve the first configuration record
        //return self::select('id', 'path')->first();
    }
}