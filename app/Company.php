<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Datakrama\Eloquid\Traits\Uuids;

class Company extends Model
{
    use Uuids, SoftDeletes;
}
