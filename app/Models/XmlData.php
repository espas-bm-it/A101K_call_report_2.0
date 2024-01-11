<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XmlData extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['SubscriberName', 'DialledNumber', 'Date', 'Time', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType'];
}
