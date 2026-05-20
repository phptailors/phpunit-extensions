<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\ArraySpec\ArraySpecInterface;


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class NodeSubjectCouple
{
    /**
     * @var array|ArraySpecInterface
     *
     * @psalm-readonly
     */
    public $node;

    /**
     * @var mixed
     *
     * @psalm-readonly
     */
    public $subject;

    /**
     * @param array|ArraySpecInterface $node
     * @param mixed $subject
     */
    public function __construct($node, $subject)
    {
        $this->node = $node;
        $this->subject = $subject;
    }
}

// vim: syntax=php sw=4 ts=4 et:
