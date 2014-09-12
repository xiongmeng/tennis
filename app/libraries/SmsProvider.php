<?php
use Sports\Finance\FinanceService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\Pdo\Pdo;

class SmsProvider extends Illuminate\Support\ServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app['sms'] = $this->app->share(function () {
            $dbConfig = \Config::get('database.connections.mysql');
            $dbConfig['driver'] = 'Mysqli';
            $dbConfig['options'] = array('buffer_results' => true);

            return new \Sports\Sms\QueueService(new Adapter(new Pdo(DB::connection()->getPdo())), false);
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('sms');
    }

}