# PHP Kafka Messaging Service
### Build Docker containers
`docker-compose up -d --build`  

This will start the containers in order to run the challenge.  
To run kafka using php, I've installed https://github.com/arnaud-lb/php-rdkafka client. 

### Run `composer install`
`docker-compose exec app composer install`

### Start Requester
`docker-compose exec app php requester.php`  

### Start Service A
`docker-compose exec app php serviceA.php`

### Start Service B
`docker-compose exec app php serviceB.php`

### Notes
I've created `docker-compose-attempt.yml` in an attempt to use Kafka Streams but due to the lack of knowledge regarding kafka, I was only able to pass messages between services.  
Therefore, I wasn't also able to use postgres.