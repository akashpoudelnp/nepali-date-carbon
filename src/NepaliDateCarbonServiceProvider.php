<?php

namespace Akashpoudelnp\NepaliDateCarbon;


use Akashpoudelnp\NepaliDateCarbon\Services\NepaliDateConverter;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class NepaliDateCarbonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Carbon::macro('convertToBS', function ($translate = false) {
            return (new NepaliDateConverter($this))->convertToBS($translate);
        });

    }

    public function register()
    {

    }
}