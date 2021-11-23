<?php

namespace App\Providers;

use App\Listeners\LogExecutedQuery;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * @inheritDoc
     */
    public function boot()
    {
        parent::boot();

        if (App::environment('local')) {
            Event::listen(QueryExecuted::class, LogExecutedQuery::class);
        }
    }
}
