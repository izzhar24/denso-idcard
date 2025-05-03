<?php
namespace App\Models;

use App\Core\Model;
class Employee extends Model
{
    protected static $table = 'employees';
    protected static $primaryKey = 'id';
}
