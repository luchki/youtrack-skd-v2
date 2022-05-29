<?php

namespace Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders;

use Luchki\YoutrackSDK\Contracts\IFieldValueHolder;
use Luchki\YoutrackSDK\Exceptions\InvalidValueException;

class MultiEnumValueHolder extends AbstractValueHolder
{
        /**
         * @return array<string>
         */
        public function getValue(): array {
                $out = [];
                /** @var IFieldValueHolder $v */
                foreach ($this->value as $v) {
                        $out[] = $v->getValue();
                }

                return $out;
        }

        public function getValueForApi(): array {
                $out = [];
                /** @var IFieldValueHolder $v */
                foreach ($this->value as $v) {
                        $out[] = ['name' => $v->getValue(), '$type' => $v->getType()];
                }

                return $out;
        }

        public function setValue(mixed $value): void {
                /** @var ArrayWithNameAndTypeValueHolder[] $value_to_set */
                $value_to_set = [];
                if (!is_array($value)) {
                        throw new InvalidValueException("Array");
                }

                foreach ($value as $v) {
                        if (!is_string($v) || is_int($v)) {
                                throw new InvalidValueException('Array<string|int>');
                        }

                        $value_holder = new ArrayWithNameAndTypeValueHolder('EnumBundleElement');
                        $value_holder->setValue($v);

                        $value_to_set[] = $value_holder;
                }

                $this->value = $value_to_set;
        }
}