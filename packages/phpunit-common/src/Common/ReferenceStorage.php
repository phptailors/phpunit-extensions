<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ReferenceStorage implements \Countable
{
    /**
     * @var array<string,mixed>
     */
    private $references = [];

    /**
     * @var \SplObjectStorage<object, null>
     */
    private $objects;

    public function __construct()
    {
        $this->objects = new \SplObjectStorage();
    }

    public function count(): int
    {
        return count($this->references) + count($this->objects);
    }

    /**
     * @param mixed $value
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out T $value
     */
    public function add(&$value): void
    {
        if (is_object($value)) {
            $this->addObject($value);

            return;
        }

        $this->addReference($value);
    }

    /**
     * @param mixed $value
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out T $value
     */
    public function remove(&$value): void
    {
        if (is_object($value)) {
            $this->removeObject($value);

            return;
        }

        $this->removeReference($value);
    }

    /**
     * @param mixed $value
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out T $value
     */
    public function contains(&$value): bool
    {
        if (is_object($value)) {
            return $this->containsObject($value);
        }

        return $this->containsReference($value);
    }

    private function addObject(object $object): void
    {
        if (!$this->objects->offsetExists($object)) {
            $this->objects->offsetSet($object);
        }
    }

    /**
     * @param mixed $value
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out T $value
     */
    private function addReference(&$value): void
    {
        $id = $this->getReferenceId($value);
        if (!array_key_exists($id, $this->references)) {
            $this->references[$id] = &$value;
        }
    }

    private function removeObject(object $object): void
    {
        if ($this->objects->offsetExists($object)) {
            $this->objects->offsetUnset($object);
        }
    }

    /**
     * @param mixed $value
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out T $value
     */
    private function removeReference(&$value): void
    {
        $id = $this->getReferenceId($value);
        if (array_key_exists($id, $this->references)) {
            unset($this->references[$id]);
        }
    }

    private function containsObject(object $object): bool
    {
        return $this->objects->offsetExists($object);
    }

    /**
     * @param mixed $value
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out T $value
     */
    private function containsReference(&$value): bool
    {
        $id = $this->getReferenceId($value);

        return array_key_exists($id, $this->references);
    }

    /**
     * @param mixed $value
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out T $value
     */
    private function getReferenceId(&$value): string
    {
        if (!class_exists(\ReflectionReference::class)) {
            /** @var mixed $reference */
            foreach ($this->references as $id => &$reference) {
                /** @var mixed */
                $backup = $reference;
                $reference = $this->objects;
                if ($value === $this->objects) {
                    /** @var mixed */
                    $reference = $backup;

                    return $id;
                }

                /** @var mixed */
                $reference = $backup;
            }

            do {
                $id = random_bytes(20);
            } while (array_key_exists($id, $this->references));

            return $id;
        }

        $ref = \ReflectionReference::fromArrayElement(get_defined_vars(), 'value');

        if (null === $ref) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw new \LogicException('Internal error');
        }

        return $ref->getId();
    }
}

// vim: syntax=php sw=4 ts=4 et:
