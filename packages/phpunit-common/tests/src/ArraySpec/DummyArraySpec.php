<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArraySpec;

use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedInput
 *
 * @template-implements ResultFactoryWrapperInterface<SupportedInput>
 * @template-extends \ArrayObject<array-key, mixed>
 */
final class DummyArraySpec extends \ArrayObject implements ResultFactoryWrapperInterface
{
    /**
     * @var ResultFactoryInterface
     *
     * @psalm-param ResultFactoryInterface<SupportedInput>
     *
     * @psalm-readonly
     */
    private $resultFactory;

    /**
     * @param array|\Traversable $array
     *
     * @psalm-param ResultFactoryInterface<SupportedInput> $resultFactory
     */
    public function __construct(ResultFactoryInterface $resultFactory, $array)
    {
        $this->resultFactory = $resultFactory;

        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }

    /**
     * @psalm-return ResultFactoryInterface<SupportedInput>
     *
     * @psalm-mutation-free
     */
    final public function getResultFactory(): ResultFactoryInterface
    {
        return $this->resultFactory;
    }
}

// vim: syntax=php sw=4 ts=4 et:
