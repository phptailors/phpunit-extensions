<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveSelectorState
{
    /**
     * @var mixed
     *
     * @psalm-readonly
     */
    public $subject;

    /**
     * @var array|ValuesInterface
     */
    public $result;

    /**
     * @param mixed                 $subject
     * @param array|ValuesInterface $result
     */
    public function __construct($subject, $result)
    {
        $this->subject = $subject;
        $this->result = $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
