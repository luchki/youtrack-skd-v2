<?php

namespace Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders;

use Luchki\YoutrackSDK\Exceptions\InvalidValueException;

class SimpleTextValueHolder extends AbstractValueHolder
{

        public function setValue(mixed $value): void {
                if (!is_string($value) && !is_int($value)) {
                        throw new InvalidValueException('String');
                }
                $this->value = $value;
        }

        public function getValue(): int|string {
                return $this->value;
        }

        public function getValueForApi(): int|string {
                return $this->value;
        }
}