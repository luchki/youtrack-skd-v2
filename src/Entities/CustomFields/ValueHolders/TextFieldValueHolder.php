<?php

namespace Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders;

use Luchki\YoutrackSDK\Exceptions\InvalidValueException;

class TextFieldValueHolder extends AbstractValueHolder
{

        function setValue(mixed $value): void {
                if (!is_string($value) && !is_int($value)) {
                        throw new InvalidValueException('String');
                }
                $this->value = $value;
        }

        public function getValue(): string|int {
                return $this->value;
        }

        public function getValueForApi(): array {
                return [
                        'text' => $this->value,
                        '$type' => $this->getType(),
                ];
        }
}