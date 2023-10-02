<?php


namespace Lessgap\Services;


class RealtimeService
{
    private $connection;

    public function __construct()
    {
        $this->connection = \DB::connection('realtime');
    }

    public function updateTableValue($table , $key, $value = null)
    {
        $exits = $this->connection->table($table)->where('key', $key)->first();
        if ($exits) {
            $this->connection->statement('UPDATE ' . $exits['id'] . ' SET value = \'' . ($value ?? uniqid()) . '\'');
        } else {
            $this->connection->statement('CREATE '.$table.' CONTENT {
    key: \'' . $key . '\',
    value: \'' . ($value ?? uniqid()) . '\',
}');
        }
    }
}
