<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Methods;

/**
 * @internal This class is not covered by the backward compatibility promise
 */
final class MethodSpecFactory implements MethodSpecFactoryInterface
{
    public const IS_PUBLIC = MethodSpec::IS_PUBLIC;
    public const IS_PROTECTED = MethodSpec::IS_PROTECTED;
    public const IS_PRIVATE = MethodSpec::IS_PRIVATE;
    public const NOT_PUBLIC = MethodSpec::NOT_PUBLIC;
    public const NOT_PROTECTED = MethodSpec::NOT_PROTECTED;
    public const NOT_PRIVATE = MethodSpec::NOT_PRIVATE;
    public const ACCESS_MAP = MethodSpec::ACCESS_MAP;

    private const RE_STATIC = '(?<static>!?static)';
    private const RE_ACCESS = '(?<access>(?:!?public)|(?:!?protected)|(?:!?private))';
    private const RE_ABSTRACT = '(?<abstract>!?abstract)';
    private const RE_FINAL = '(?<final>!?final)';
    private const RE_IDENT = '[a-zA-z_][0-9a-zA-Z_]*';
    private const RE_NAME = '(?<name>'.self::RE_IDENT.')';

    /**
     * Parses string containing method requirement specification.
     *
     * @return MethodSpec method specification created from string or null on error
     *
     * @throws MethodSpecSyntaxError
     */
    public function fromString(string $string): MethodSpec
    {
        if (preg_match('/^'.self::RE_NAME.'$/', $string, $matches)) {
            /** @psalm-var non-empty-string */
            $name = $matches['name'];

            return new MethodSpec($name);
        }

        $expressions = [
            self::RE_ABSTRACT,
            self::RE_FINAL,
            self::RE_STATIC,
            self::RE_ACCESS,
        ];

        $static = $final = $abstract = $access = null;
        $i = 4; // prevent endless loop (in case of a bug).
        while ($this->lookahead($expressions, $string, $matches) && $i > 0) {
            $this->handleModifiers($string, $matches, $abstract, $final, $static, $access);
            $string = ltrim($string);
            --$i;
        }

        $expressions = ['function'];
        if (!$this->lookahead($expressions, $string, $matches)) {
            throw new MethodSpecSyntaxError(sprintf('syntax error at "%s"', $string));
        }
        $string = ltrim($string);

        $expressions = [self::RE_NAME];
        if (!$this->lookahead($expressions, $string, $matches)) {
            throw new MethodSpecSyntaxError(sprintf('syntax error at "%s"', $string));
        }

        /** @psalm-var non-empty-string */
        $name = $matches['name'];

        if ('' !== $string) {
            throw new MethodSpecSyntaxError(sprintf('syntax error at "%s"', $string));
        }

        return new MethodSpec($name, $static, $access, $abstract, $final);
    }

    /**
     * @psalm-param array<string> $expressions
     *
     * @psalm-param-out array<string> $matches
     */
    private function lookahead(array &$expressions, string &$string, array &$matches = null): bool
    {
        foreach ($expressions as $offset => $expression) {
            if (preg_match('/^'.$expression.'\b/', $string, $matches)) {
                $string = substr($string, strlen($matches[0]));
                unset($expressions[$offset]);

                return true;
            }
        }

        $matches = [''];

        return false;
    }

    /**
     * @throws MethodSpecSyntaxError
     *
     * @psalm-param array<string> $matches
     */
    private function handleModifiers(
        string $string,
        array $matches,
        ?bool &$abstract = null,
        ?bool &$final = null,
        ?bool &$static = null,
        ?int &$access = null
    ): void {
        $this->handleBoolModifier('abstract', $matches, $abstract);
        $this->assertAbstractFinalConsistent($string, $matches, $abstract, $final);
        $this->handleBoolModifier('final', $matches, $final);
        $this->assertAbstractFinalConsistent($string, $matches, $abstract, $final);
        $this->handleBoolModifier('static', $matches, $static);
        $this->handleAccessModifier($matches, $access);
    }

    /**
     * @throws MethodSpecSyntaxError
     *
     * @psalm-param array<string> $matches
     */
    private function assertAbstractFinalConsistent(string $string, array $matches, ?bool $abstract, ?bool $final): void
    {
        if (null !== $abstract && null !== $final && ($abstract || $final)) {
            if ('' === ($val = $matches['abstract'] ?? '')) {
                $val = $matches['final'] ?? '';
            }
            $at = $val.$string;

            throw new MethodSpecSyntaxError(sprintf('syntax error at "%s"', $at));
        }
    }

    /**
     * @psalm-param array<string> $matches
     */
    private function handleBoolModifier(string $key, array $matches, ?bool &$output = null): void
    {
        if ('' !== ($match = ($matches[$key] ?? ''))) {
            $output = $key === $match; // otherwise it's "!$key"
        }
    }

    /**
     * @psalm-param array<string> $matches
     */
    private function handleAccessModifier(array $matches, ?int &$access = null): void
    {
        if ('' !== ($key = ($matches['access'] ?? '')) && array_key_exists($key, self::ACCESS_MAP)) {
            $access = self::ACCESS_MAP[$key];
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
