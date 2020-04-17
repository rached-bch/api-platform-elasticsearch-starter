<?php

namespace App\Elasticsearch;

use Elasticsearch\ClientBuilder;
use Psr\Container\ContainerInterface;
use App\Lib\Utils;
use Symfony\Component\Dotenv\Dotenv;

class ClientManager
{
    private $client;
    private $utils;
    private $container;
    private $elasticsearch_mapping_entities = [];
    private $dotenv;

    public function __construct(Utils $utils, ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->utils = $utils;
        if ($this->container->hasParameter('elasticsearch_mapping_entities')) {
            $this->elasticsearch_mapping_entities = $this->container->getParameter('elasticsearch_mapping_entities');
        }
        $this->client = ClientBuilder::create()->setHosts([$this->getHost()])->build();
        $this->dotenv = new Dotenv();
        $this->dotenv->load(dirname(dirname(__DIR__)).'/.env');
    }

    public function getHost()
    {
        if ($_ENV['ELASTICSEARCH_CONTAINER_HOST'] ?? null) {
            return $_ENV['ELASTICSEARCH_CONTAINER_HOST'];
        }

        return 'localhost:9200';
    }

    public function getClient()
    {
        return $this->client;
    }

    public function createDocumentFromEntity($entity)
    {
        $class_name = \get_class($entity);
        if (isset($this->elasticsearch_mapping_entities[$class_name])) {
            $index_name = $this->elasticsearch_mapping_entities[$class_name]['index'];
            $params = [
                'index' => $index_name,
                'type' => '_doc',
                'id' => $entity->getId(),
                'body' => $this->getBodyParametersFromEntity($entity),
            ];

            return $this->getClient()->index($params);
        }

        return null;
    }

    public function updateDocumentFromEntity($entity)
    {
        $class_name = \get_class($entity);
        if (isset($this->elasticsearch_mapping_entities[$class_name])) {
            $index_name = $this->elasticsearch_mapping_entities[$class_name]['index'];
            $params = [
                'index' => $index_name,
                'type' => '_doc',
                'id' => $entity->getId(),
                'body' => $this->getBodyParametersFromEntity($entity),
            ];

            return $this->getClient()->index($params);
        }

        return null;
    }

    public function deleteDocumentFromEntity($entity)
    {
        $class_name = \get_class($entity);
        if (isset($this->elasticsearch_mapping_entities[$class_name])) {
            $index_name = $this->elasticsearch_mapping_entities[$class_name]['index'];
            $params = [
                'index' => $index_name,
                'type' => '_doc',
                'id' => $entity->getId(),
            ];

            return $this->getClient()->delete($params);
        }

        return null;
    }

    public function getBodyParametersFromEntity($entity): array
    {
        $body = [];
        $class_name = \get_class($entity);
        if (isset($this->elasticsearch_mapping_entities[$class_name])) {
            foreach ($this->elasticsearch_mapping_entities[$class_name]['fields'] as $field) {
                $get_field_method = 'get'.$this->utils->convertStringToCamelCase($field);
                $body[$field] = $entity->{$get_field_method }();
            }
        }

        return $body;
    }
}
