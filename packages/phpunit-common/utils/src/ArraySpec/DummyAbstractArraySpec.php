<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArraySpec;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedInput
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @template-extends AbstractArraySpec<SupportedInput>
 */
final class DummyAbstractArraySpec extends AbstractArraySpec
{
    /**
     * @psalm-param ResultFactoryInterface<SupportedInput> $resultFactory
     * @psalm-param ArrayLike                              $array
     */
    public function __construct(ResultFactoryInterface $resultFactory, iterable $array)
    {
        parent::__construct($resultFactory, $array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
