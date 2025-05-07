<?php

namespace App\Models;

use App\Core\Model;

class RequestEmployeeCard extends Model
{
    protected static $table = 'request_employee_card';
    protected static $primaryKey = 'id';

    public function employee_card($row)
    {
        // return $this->belongsTo(EmployeeCard::class, 'employee_card_id');
        return EmployeeCard::table()->where('id', $row->employee_card_id)->first();
    }
}
