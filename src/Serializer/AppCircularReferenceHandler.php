<?php

namespace App\Serializer;

use App\Entity\EntityInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;

class AppCircularReferenceHandler
{
    public static function handle(object $object, ?string $format = null, array $context = []): string
    {
        if (!$object instanceof EntityInterface) {
            throw new CircularReferenceException('A circular reference was detected');
        }

        return $object->getId();
    }
}
