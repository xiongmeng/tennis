<?php

class FinanceServiceProvider extends Illuminate\Support\ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app['finance'] = $this->app->share(function () {
            $dbConfig = \Config::get('database.connections.mysql');
            $dbConfig['driver'] = 'Mysqli';
            $dbConfig['options'] = array('buffer_results' => true);

            return new \Sports\Finance\FinanceService(new \Zend\Db\Adapter\Adapter($dbConfig));
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('finance');
    }

}