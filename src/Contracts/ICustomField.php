<?php

namespace Luchki\YoutrackSDK\Contracts;

interface ICustomField
{
        public function getName(): string;

        public function getType(): string;

        public function getValue(): mixed;

        public function toArray(): array;
}