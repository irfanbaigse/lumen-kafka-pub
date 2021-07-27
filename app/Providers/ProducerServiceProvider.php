<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RdKafka\Conf;
use RdKafka\Producer;

/**
 * Class ProducerServiceProvider
 *
 * @package \App\Providers
 */
class ProducerServiceProvider extends ServiceProvider
{
    /**
     * Boot method
     *
     * @return void
     */
    public function boot()
    {
        $conf = new Conf;
        $conf->set('metadata.broker.list', env('KAFKA_PRODUCER_BROKERS', '127.0.0.1'));
        $conf->set('compression.type', env('KAFKA_PRODUCER_COMPRESSION', 'snappy'));

        /**
         * optimized for low latency.
         */
        /*
         * ext-pcntl -- if enabled
         * $conf->set('socket.timeout.ms', 50); // or socket.blocking.max.ms, depending on librdkafka version
        if (function_exists('pcntl_sigprocmask')) {
            pcntl_sigprocmask(SIG_BLOCK, array(SIGIO));
            $conf->set('internal.termination.signal', SIGIO);
        } else {
            $conf->set('queue.buffering.max.ms', 1);
        }*/

        if (filter_var(config('KAFKA_PRODUCER_DEBUG', false), FILTER_VALIDATE_BOOLEAN)) {
            $conf->set('log_level', LOG_DEBUG);
            $conf->set('debug', 'all');
        }

//        dd($conf->dump());

        $this->app->bind(Producer::class, function () use ($conf) {
            return new Producer($conf);
        });
    }
}
