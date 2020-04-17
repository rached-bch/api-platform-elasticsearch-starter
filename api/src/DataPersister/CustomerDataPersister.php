<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\ClientBuilder;

final class CustomerDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $client;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->client = ClientBuilder::create()->setHosts(['es01:9200'])->build();
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Customer;
    }

    public function persist($data, array $context = [])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        if (isset($context['collection_operation_name']) && $context['collection_operation_name'] === 'post' && isset($context['persist']) && $context['persist'] === true) {
            $params = [
                'index' => 'customer',
                'type' => '_doc',
                'id' => $data->getId(),
                'body' => [
                    'first_name' => $data->getFirstname(),
                    'last_name' => $data->getLastname(),
                ],
            ];

            $response = $this->client->index($params);
        }

        return $data;
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
