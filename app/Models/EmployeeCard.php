<?php

namespace App\Models;

use App\Core\Model;

class EmployeeCard extends Model
{
    protected static $table = 'employee_card';
    protected static $primaryKey = 'id';

    public function employee($row)
    {
        return Employee::table()->where('id', $row->employee_id)->first();
    }
}
