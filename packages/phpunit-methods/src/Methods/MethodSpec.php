<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Methods;

/**
 * Specifies requirements on method including its name and modifiers.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-type AccessStr = ("public"
 *                       | "protected"
 *                       | "private"
 *                       | "!public"
 *                       | "!protected"
 *                       | "!private")
 * @psalm-type AccessInt = (self::IS_PUBLIC
 *                       |  self::IS_PROTECTED
 *                       |  self::IS_PRIVATE
 *                       |  self::NOT_PUBLIC
 *                       |  self::NOT_PROTECTED
 *                       |  self::NOT_PRIVATE )
 */
final readonly class MethodSpec implements MethodSpecInterface
{
    public const IS_STATIC = \ReflectionMethod::IS_STATIC;
    public const IS_PUBLIC = \ReflectionMethod::IS_PUBLIC;
    public const IS_PROTECTED = \ReflectionMethod::IS_PROTECTED;
    public const IS_PRIVATE = \ReflectionMethod::IS_PRIVATE;
    public const IS_ABSTRACT = \ReflectionMethod::IS_ABSTRACT;
    public const IS_FINAL = \ReflectionMethod::IS_FINAL;

    public const ACCESS_MASK = self::IS_PUBLIC | self::IS_PROTECTED | self::IS_PRIVATE;

    public const NOT_PUBLIC = self::IS_PROTECTED | self::IS_PRIVATE;
    public const NOT_PROTECTED = self::IS_PUBLIC | self::IS_PRIVATE;
    public const NOT_PRIVATE = self::IS_PUBLIC | self::IS_PROTECTED;

    /**
     * @psalm-var array<AccessStr,AccessInt>
     */
    public const ACCESS_MAP = [
        'public'     => self::IS_PUBLIC,
        'protected'  => self::IS_PROTECTED,
        'private'    => self::IS_PRIVATE,
        '!public'    => self::NOT_PUBLIC,
        '!protected' => self::NOT_PROTECTED,
        '!private'   => self::NOT_PRIVATE,
    ];

    /**
     * @psalm-param non-empty-string $name
     */
    public function __construct(
        /**
         * @psalm-var non-empty-string
         *
         * @psalm-readonly
         */
        private string $name,
        /**
         * @psalm-readonly
         */
        private ?bool $static = null,
        /**
         * @psalm-readonly
         */
        private ?int $access = null,
        /**
         * @psalm-readonly
         */
        private ?bool $abstract = null,
        /**
         * @psalm-readonly
         */
        private ?bool $final = null
    ) {}

    /**
     * {@inheridoc}.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheridoc}.
     */
    public function getStatic(): ?bool
    {
        return $this->static;
    }

    /**
     * {@inheridoc}.
     */
    public function getAccess(): ?int
    {
        return $this->access;
    }

    /**
     * {@inheridoc}.
     */
    public function getAbstract(): ?bool
    {
        return $this->abstract;
    }

    /**
     * {@inheridoc}.
     */
    public function getFinal(): ?bool
    {
        return $this->final;
    }

    /**
     * {@inheridoc}.
     */
    public function matches(\ReflectionMethod $method): bool
    {
        return $this->matchName($method)
            && $this->matchStatic($method)
            && $this->matchAccess($method)
            && $this->matchAbstract($method)
            && $this->matchFinal($method);
    }

    /**
     * {@inheridoc}.
     */
    public function toString(): string
    {
        $final = $this->getFinalModifierString();
        $abstract = $this->getAbstractModifierString();
        $access = $this->getAccessModifierString();
        $static = $this->getStaticModifierString();

        return $final.$abstract.$access.$static.'method '.$this->getName();
    }

    private function matchName(\ReflectionMethod $method): bool
    {
        return $this->getName() === $method->name;
    }

    private function matchStatic(\ReflectionMethod $method): bool
    {
        return null === $this->getStatic() || $method->isStatic() === $this->getStatic();
    }

    private function matchAccess(\ReflectionMethod $method): bool
    {
        return null === $this->getAccess() || ($method->getModifiers() & self::ACCESS_MASK) === $this->getAccess();
    }

    private function matchAbstract(\ReflectionMethod $method): bool
    {
        return null === $this->getAbstract() || $method->isAbstract() === $this->getAbstract();
    }

    private function matchFinal(\ReflectionMethod $method): bool
    {
        return null === $this->getFinal() || $method->isFinal() === $this->getFinal();
    }

    private function getStaticModifierString(): string
    {
        return true === $this->getStatic() ? 'static ' : (false === $this->getStatic() ? '!static ' : '');
    }

    /**
     * @psalm-mutation-free
     */
    private function getAccessModifierString(): string
    {
        if (null === $this->getAccess()) {
            return '';
        }

        foreach (self::ACCESS_MAP as $key => $bits) {
            if ($this->getAccess() === $bits) {
                return $key.' ';
            }
        }

        return '';
    }

    private function getAbstractModifierString(): string
    {
        return true === $this->getAbstract() ? 'abstract ' : (false === $this->getAbstract() ? '!abstract ' : '');
    }

    private function getFinalModifierString(): string
    {
        return true === $this->getFinal() ? 'final ' : (false === $this->getFinal() ? '!final ' : '');
    }
}

// vim: syntax=php sw=4 ts=4 et:
