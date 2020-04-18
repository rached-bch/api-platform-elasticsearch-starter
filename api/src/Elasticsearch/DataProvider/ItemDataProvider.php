<?php

namespace App\Elasticsearch\DataProvider;

use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\ItemDataProvider as ElasticsearchItemDataProvider;
use ApiPlatform\Core\Bridge\Elasticsearch\Api\IdentifierExtractorInterface;
use ApiPlatform\Core\Bridge\Elasticsearch\Metadata\Document\Factory\DocumentMetadataFactoryInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use Elasticsearch\Client;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $elasticsearchItemDataProvider;
    private $client;
    private $documentMetadataFactory;
    private $identifierExtractor;
    private $denormalizer;
    private $resourceMetadataFactory;
    const OPERATIONS_TO_DISABLE = ['patch', 'put', 'delete'];

    public function __construct(ElasticsearchItemDataProvider $elasticsearchItemDataProvider, Client $client, DocumentMetadataFactoryInterface $documentMetadataFactory, IdentifierExtractorInterface $identifierExtractor, DenormalizerInterface $denormalizer, ResourceMetadataFactoryInterface $resourceMetadataFactory)
    {
        $this->elasticsearchItemDataProvider = $elasticsearchItemDataProvider;
        $this->client = $client;
        $this->documentMetadataFactory = $documentMetadataFactory;
        $this->identifierExtractor = $identifierExtractor;
        $this->denormalizer = $denormalizer;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        // this line disable provider  <<
        if (\in_array($operationName, self::OPERATIONS_TO_DISABLE)) {
            return false;
        }

        return $this->elasticsearchItemDataProvider->supports($resourceClass, $operationName, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        return $this->elasticsearchItemDataProvider->getItem($resourceClass, $id, $operationName, $context);
    }
}
