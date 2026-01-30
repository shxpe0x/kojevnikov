<?php

declare(strict_types=1);

namespace App\DTOs;

abstract class DTO
{
    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $data = [];
        foreach ($properties as $property) {
            $data[$property->getName()] = $property->getValue($this);
        }

        return $data;
    }

    /**
     * Create DTO from array.
     */
    public static function fromArray(array $data): static
    {
        $reflection = new \ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();
        
        if (!$constructor) {
            return new static();
        }

        $params = [];
        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $params[] = $data[$name] ?? ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
        }

        return new static(...$params);
    }
}
