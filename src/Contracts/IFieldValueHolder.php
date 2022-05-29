<?php

namespace Luchki\YoutrackSDK\Contracts;

interface IFieldValueHolder
{
        public function __construct(string $type);

        public function getValue(): mixed;

        public function getValueForApi(): mixed;

        public function setValue(mixed $value): void;

        public function getType(): string;
}