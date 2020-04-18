<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use App\Elasticsearch\ClientManager;

final class CustomerDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $client;
    private $clientManager;

    public function __construct(EntityManagerInterface $entityManager, ClientManager $clientManager)
    {
        $this->entityManager = $entityManager;
        $this->clientManager = $clientManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Customer;
    }

    public function persist($data, array $context = [])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        if ($this->isPostRequest($context)) {
            $this->clientManager->createDocumentFromEntity($data);
        } elseif ($this->isPutRequest($context)) {
            $this->clientManager->updateDocumentFromEntity($data);
        } elseif ($this->isPatchRequest($context)) {
            $this->clientManager->deleteDocumentFromEntity($data);
            $this->clientManager->updateDocumentFromEntity($data);
        }

        return $data;
    }

    public function remove($data, array $context = [])
    {
        $user_account = clone $data;

        $this->entityManager->remove($data);
        $this->entityManager->flush();

        $this->clientManager->deleteDocumentFromEntity($user_account);
    }

    private function isPostRequest(array $context = []): bool
    {
        if (isset($context['collection_operation_name']) && $context['collection_operation_name'] === 'post' && isset($context['persist']) && $context['persist'] === true) {
            return true;
        }

        return false;
    }

    private function isPutRequest(array $context = []): bool
    {
        if (isset($context['item_operation_name']) && $context['item_operation_name'] === 'put' && isset($context['persist']) && $context['persist'] === true) {
            return true;
        }

        return false;
    }

    private function isPatchRequest(array $context = []): bool
    {
        if (isset($context['item_operation_name']) && $context['item_operation_name'] === 'patch' && isset($context['persist']) && $context['persist'] === true) {
            return true;
        }

        return false;
    }
}
