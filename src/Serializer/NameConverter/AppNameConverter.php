<?php

namespace App\Serializer\NameConverter;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\NameConverter\AdvancedNameConverterInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\SnakeCaseToCamelCaseNameConverter;

class AppNameConverter implements AdvancedNameConverterInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.name_converter.camel_case_to_snake_case')]
        private readonly CamelCaseToSnakeCaseNameConverter $camalCaseNameConverter,
        #[Autowire(service: 'serializer.name_converter.snake_case_to_camel_case')]
        private readonly SnakeCaseToCamelCaseNameConverter $snakeCaseNameConverter
    )
    {
    }

    public function normalize(string $propertyName, ?string $class = null, ?string $format = null, array $context = []): string
    {
        $propertyName = $this->camalCaseNameConverter->normalize($propertyName);

        if (\array_key_exists('private_api', $context) && true === $context['private_api']) {
            return $propertyName;
        }

        return 'app_'.$propertyName;
    }

    public function denormalize(string $propertyName, ?string $class = null, ?string $format = null, array $context = []): string
    {
        $propertyName = $this->snakeCaseNameConverter->denormalize($propertyName);

        if (\array_key_exists('private_api', $context) && true === $context['private_api']) {
            return $propertyName;
        }

        return str_starts_with($propertyName, 'app_') ? substr($propertyName, 4) : $propertyName;
    }
}
