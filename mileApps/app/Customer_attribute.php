<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Customer_attribute extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'destination_data';
    protected $fillable   = [
        "nama_sales",
        "top",
        "jenis_pelanggan",
    ];
}
