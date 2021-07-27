<?php

namespace App\Console\Commands;

use App\Handlers\ProducerHandler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class ProducerCommand
 *
 * @package \\${NAMESPACE}
 */
class ProducerCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "kafka:run-producer
                            {--topic= : The topic name of the queues to push}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "run sample producer 1-10 messages";

    /**
     * Topic name
     */
    protected $kafkaTopic = 'test';

    /**
     * Publish error message
     */
    const PUBLISH_ERROR_MESSAGE = 'Publish message to kafka failed';

    /**
     * Kafka producer
     *
     * @var \App\Handlers\ProducerHandler
     */
    protected $producerHandler;

    /**
     * ProducerCommand constructor.
     *
     * @param \App\Handlers\ProducerHandler $producerHandler
     */
    public function __construct(ProducerHandler $producerHandler)
    {
        parent::__construct();

        $this->producerHandler = $producerHandler;
    }

    public function handle(ProducerHandler $producerHandler)
    {
        $this->info("-------- starting producer --------");


        $topic = $this->option("topic");

        $this->setKafkaTopic($topic);

        for ($i = 0; $i < 10; $i++) {
            $this->pushToKafka(['id' => $i + 1, 'message' => 'any thing']);
        }

        $this->info("--------------- end ---------------");
    }

    /**
     * @return string
     */
    protected function getKafkaTopic(): string
    {
        return $this->kafkaTopic;
    }

    /**
     * Push inventory to kafka
     *
     * @param array  $order
     * @param string $type
     *
     * @return void
     */
    protected function pushToKafka(array $data)
    {
        $id = $data['id'] ?? '';

        try {
            $this->producerHandler->setTopic($this->getKafkaTopic())
                                  ->send(json_encode($data), $id);
        } catch (\Exception $e) {
            $message = sprintf(
                '%s loop id: %s message: %s',
                self::PUBLISH_ERROR_MESSAGE,
                $id,
                $e->getMessage()
            );
            Log::critical($message, ['kafka.push']);
        }
    }

    /**
     * @param string $kafkaTopic
     */
    protected function setKafkaTopic(string $kafkaTopic)
    {
        $this->kafkaTopic = $kafkaTopic;
    }

}
