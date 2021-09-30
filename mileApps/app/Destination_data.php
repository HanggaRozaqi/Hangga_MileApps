<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Destination_data extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'destination_data';
    protected $fillable   = [
        "transaction_id",
        "customer_name",
        "customer_address",
        "customer_email",
        "customer_phone",
        "customer_address_detail",
        "customer_zip_code",
        "zone_code",
        "organization_id",
        "location_id",
    ];
}
