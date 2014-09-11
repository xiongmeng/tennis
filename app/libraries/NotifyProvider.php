<?php
use Sports\Finance\FinanceService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\Pdo\Pdo;

class NotifyProvider extends Illuminate\Support\ServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app['notify'] = $this->app->share(function () {
            return new NotifyService(Config::get('notify.events'), Config::get('notify.channels'));
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('notify');
    }

}