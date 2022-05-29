<?php

namespace Luchki\YoutrackSDK\Entities\CustomFields;

use Luchki\YoutrackSDK\Contracts\ICustomField;
use Luchki\YoutrackSDK\Contracts\IFieldValueHolder;
use Luchki\YoutrackSDK\Exceptions\InvalidValueException;

abstract class CustomField implements ICustomField
{
        private string $name;
        private IFieldValueHolder $value_holder;
        private string $type;

        public function __construct(string $name, string $type, mixed $value) {
                $this->setName($name);
                $this->setType($type);
                $this->setValue($value);
        }

        /**
         * @return string
         */
        public function getName(): string {
                return $this->name;
        }
        /**
         * @param string $name
         */
        private function setName(string $name): void {
                $this->name = $name;
        }
        /**
         * @return mixed
         */
        public function getValue(): mixed {
                return $this->getValueHolder()->getValue();
        }

        /**
         * @param mixed $value
         */
        private function setValue(mixed $value): void {
                $this->value_holder = $this->makeValueHolder($this->type, $value);
                $this->getValueHolder()->setValue($value);
        }

        public function getType(): string {
                return $this->type;
        }

        /**
         * @param string $type
         */
        private function setType(string $type): void {
                $this->type = $type;
        }

        public function toArray(): array {
                return [
                        'name' => $this->getName(),
                        '$type' => $this->getType(),
                        'value' => $this->getValueHolder()->getValueForApi(),
                ];
        }

        abstract protected function makeValueHolder(string $field_type, mixed $value): IFieldValueHolder;

        private function getValueHolder(): IFieldValueHolder {
                return $this->value_holder;
        }
}