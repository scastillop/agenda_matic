<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaseModel extends Model
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
