<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use Tailors\PHPUnit\ArraySpec\DummyResultFactoryAndValueSelectorWrapper;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactory;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;
use Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;

/**
 * Example constraint class that extends the AbstractConstraint.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ExampleRecursiveConstraint extends AbstractRecursiveConstraint
{
    /**
     * @psalm-param ArrayLike $expectations
     */
    public static function create(iterable $expectations): self
    {
        $expectations = new DummyResultFactoryAndValueSelectorWrapper(
            new DummyArrayResultFactory(),
            new DummyValueSelector(
                /**
                 * @param mixed $subject
                 */
                function ($subject): bool {
                    return is_array($subject);
                },
                /**
                 * @param mixed $key
                 * @param mixed $retval
                 *
                 * @psalm-param array-key $key
                 *
                 * @psalm-param-out mixed $retval
                 */
                function (array $subject, $key, &$retval) {
                    if (!array_key_exists($key, $subject)) {
                        return false;
                    }

                    /** @psalm-var mixed $retval */
                    $retval = $subject[$key];

                    return true;
                },
                'an array',
                'values'
            ),
            $expectations
        );

        return new self($expectations, new IdentityComparator(), RecursiveResultFactory::create(), RecursiveResultUnwrapper::create());
    }
}

// vim: syntax=php sw=4 ts=4 et:
