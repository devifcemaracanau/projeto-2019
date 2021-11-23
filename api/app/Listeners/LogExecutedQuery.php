<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class LogExecutedQuery
{
    public function handle(QueryExecuted $query)
    {
        Log::channel('stdout')->info(
            $query->sql,
            [
                'bidings' => $query->bindings,
                'time'    => "{$query->time}ms"
            ]
        );
    }
}
