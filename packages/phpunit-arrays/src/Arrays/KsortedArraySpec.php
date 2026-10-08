<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\ArraySpec\AbstractArraySpec;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Predicate\ComparatorAwareComplementInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @template-extends AbstractArraySpec<ArrayLike>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class KsortedArraySpec extends AbstractArraySpec implements ComparatorAwareComplementInterface
{
    /**
     * @psalm-param ArrayLike $array
     */
    public function __construct(iterable $array, int $flags = SORT_REGULAR)
    {
        parent::__construct(new KsortedArrayFactory($flags), $array);
    }

    public function complement(ComparatorInterface $comparator): string
    {
        return sprintf('an array %s specified one when ksorted', $comparator->adjective());
    }
}

// vim: syntax=php sw=4 ts=4 et:
