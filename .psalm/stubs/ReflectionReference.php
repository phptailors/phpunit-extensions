<?php

/**
 * @since 7.4
 */
final class ReflectionReference
{
    private function __construct() {}
    /**
     * @param array $array
     */
    public static function fromArrayElement(array $array, string|int $key): ?ReflectionReference {}
    public function getId(): string {}
    private function __clone(): void {}
}
