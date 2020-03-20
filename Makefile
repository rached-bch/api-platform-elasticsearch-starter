start:
	COMPOSE_HTTP_TIMEOUT=900 docker-compose build
	COMPOSE_HTTP_TIMEOUT=900 docker-compose up

stop:
	docker-compose stop

reset:
	docker container stop $$(docker-compose ps -aq)
	docker rm $$(docker-compose ps -aq)
	docker volume prune -f
