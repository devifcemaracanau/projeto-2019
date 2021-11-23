<?php

namespace App\Console;

use App\Console\Commands\CNES\AtualizarCBO;
use App\Console\Commands\CNES\AtualizarInstituicoes;
use App\Console\Commands\CNES\AtualizarMantenedoras;
use App\Console\Commands\CNES\AtualizarNaturezasJuridicas;
use App\Console\Commands\CNES\AtualizarProfissionais;
use App\Console\Commands\CNES\AtualizarTiposUnidades;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AtualizarCBO::class,
        AtualizarInstituicoes::class,
        AtualizarMantenedoras::class,
        AtualizarNaturezasJuridicas::class,
        AtualizarProfissionais::class,
        AtualizarTiposUnidades::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
