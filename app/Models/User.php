<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use PDO;

class User extends Model
{
    protected static $table = 'users';
    protected static $primaryKey = 'id';
}
