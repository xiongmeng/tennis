<?php

class AreaProvider extends Illuminate\Support\ServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app['area'] = $this->app->share(function () {
            return new AreaService();
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('area');
    }

}