<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Result\ResultInterface;


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends \ArrayObject<mixed,mixed>
 */
final class DummyArrayResult extends \ArrayObject implements ResultInterface, TagInterface
{
    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $actual;

    /**
     * @var ?string
     */
    private $tag;

    /**
     * @param array|\Traversable $array
     */
    public function __construct(bool $actual, $array = [], ?string $tag = null)
    {
        $this->actual = $actual;
        $this->tag = $tag;

        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }

    /**
     * @psalm-mutation-free
     */
    public function actual(): bool
    {
        return $this->actual;
    }

    /**
     * @psalm-return non-empty-string
     *
     * @psalm-mutation-free
     */
    public function tag(): string
    {
        return $this->tag ?? self::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';
    }
}

// vim: syntax=php sw=4 ts=4 et:
