<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Koli extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'koli';
    protected $fillable   = [
        "transaction_id",
        "koli_length",
        "awb_url",
        "created_at",
        "koli_chargeable_weight",
        "koli_width",
        "koli_surcharge",
        "koli_height",
        "updated_at",
        "koli_description",
        "koli_formula_id",
        "connote_id",
        "koli_volume",
        "koli_weight",
        "koli_id",
        "koli_custom_field",
        "koli_code",
    ];
}
