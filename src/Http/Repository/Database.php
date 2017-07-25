<?php
namespace Orbas\Stage\Http\Repository;

use Illuminate\Support\Facades\DB;

class Database
{
    public function getTables()
    {
        $tables = DB::select('SHOW TABLES');
        $name   = 'Tables_in_' . DB::getDatabaseName();

        return collect($tables)->pluck($name);
    }

    public function getColumns($table)
    {
        return DB::select('show columns from ' . $table);
    }
}