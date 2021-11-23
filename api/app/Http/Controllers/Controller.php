<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    public function index() {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            echo 'No connection';
        }
    }
}
