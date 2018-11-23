<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class BaseAuthenticatable extends Authenticatable
{
    /**
     * @return string date format for mssql
     */
    public function getDateFormat()
    {
        return 'Y-d-m H:i:s.v';
    }

    public function freshTimestamp()
    {
        return Carbon::now('America/Santiago');
    }
}
