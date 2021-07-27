# lumen-kafka-pub
lumen Kafka publisher

## To produce

```shell
$ php artisan kafka:run-producer
```

## To consume

```shell
$ kafka-console-consumer --bootstrap-server 127.0.0.1:9092 --topic your_topic --from-beginning --group your_group_name
```

---

### Install Kafka using brew on MAC OS

```shell
$ brew install kafka
```


### Run ZK and kafka as services
```shell
$ brew services start zookeeper
$ brew services start kafka
```

### To stop ZK and kafka

```shell
$ brew services stop kafka
$ brew services stop zookeeper
```

### Alternative run

```shell
$ zookeeper-server-start /usr/local/etc/kafka/zookeeper.properties
$ kafka-server-start /usr/local/etc/kafka/server.properties
```
