<?php

namespace App\Doctrine;

use Doctrine\ORM\Mapping\Entity;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Doctrine\UuidGenerator;

class CustomUuidGenerator extends UuidGenerator
{
    /**
     * Generate an identifier.
     *
     * @param EntityManager $em
     * @param Entity        $entity
     *
     * @return UuidInterface
     *
     * @throws Exception
     */
    public function generate(EntityManager $em, $entity)
    {
        if ($entity->getId() === null) {
            return self::getUuid();
        } else {
            return $entity->getId();
        }
    }

    public static function getUuid()
    {
        return Uuid::uuid4();
    }
}
