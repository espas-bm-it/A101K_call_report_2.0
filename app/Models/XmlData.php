<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class XmlData extends Model
{
    use HasFactory, Sortable;

    public $timestamps = false;

    protected $dates = [];
    
    protected $fillable = ['SubscriberName', 'DialledNumber', 'Date', 'Time', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType'];

    protected $sortable = ['Date','Zeit', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType'];

}
