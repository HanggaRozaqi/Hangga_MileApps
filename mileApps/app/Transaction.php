<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Transaction extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'transaction';
    protected $fillable   = [
        "transaction_id",
        "customer_name",
        "customer_code",
        "transaction_amount",
        "transaction_discount",
        "transaction_additional_field",
        "transaction_payment_type",
        "transaction_state",
        "transaction_code",
        "transaction_order",
        "location_id",
        "organization_id",
        "created_at",
        "updated_at",
        "transaction_payment_type_name",
        "transaction_cash_amount",
        "transaction_cash_change",
        "customer_attribute",
        "custom_field",
        "currentLocation",
        "connote_id"
    ];

    public function connote()
    {
        return $this->hasOne('App\Connote', 'transaction_id', 'transaction_id');
    }

    public function origin_data()
    {
        return $this->hasOne('App\Origin_data', 'transaction_id', 'transaction_id');
    }

    public function koli()
    {
        return $this->hasMany('App\Koli', 'transaction_id', 'transaction_id');
    }

    public function destination_data()
    {
        return $this->hasOne('App\Destination_data', 'transaction_id', 'transaction_id');
    }
}
