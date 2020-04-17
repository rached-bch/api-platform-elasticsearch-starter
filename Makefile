build:
	COMPOSE_HTTP_TIMEOUT=900 docker-compose build
start:
	COMPOSE_HTTP_TIMEOUT=900 docker-compose up
setup-elasticsearch-mapping:
	curl -XPUT "http://localhost:9200/customer?include_type_name=true" -H 'Content-Type:application/json'-d'{"mappings":{"_doc":{"properties":{"id":{"type":"keyword"},"first_name":{"type":"text"},"last_name":{"type":"text"}},"dynamic":"strict"}}}'
