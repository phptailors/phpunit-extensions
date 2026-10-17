<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Common\AbstractArrayExpectations;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Predicate\ComparatorAwareComplementInterface;
use Tailors\PHPUnit\RecursiveMapper\RecursiveMapper;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @template-extends AbstractArrayExpectations<ArrayLike>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursivelyKsortedArrayExpectations extends AbstractArrayExpectations implements ComparatorAwareComplementInterface
{
    /**
     * @psalm-param ArrayLike $array
     */
    public static function create(iterable $array, int $flags = SORT_REGULAR): self
    {
        $mapper = RecursiveMapper::create();

        $mapped = $mapper->map(
            $array,
            /**
             * @psalm-param ArrayLike $node
             */
            function (iterable &$node, array $stack) use ($flags): bool {
                if (!is_array($node)) {
                    return $node instanceof ResultFactoryWrapperInterface;
                }

                if ([] !== $stack) {
                    $node = new KsortedArrayExpectations([], $flags);
                }

                return true;
            }
        );

        return new self(new KsortedArrayFactory($flags), $mapped);
    }

    public function complement(ComparatorInterface $comparator): string
    {
        return sprintf('an array %s the specified one when ksorted recursively', $comparator->adjective());
    }
}

// vim: syntax=php sw=4 ts=4 et:
