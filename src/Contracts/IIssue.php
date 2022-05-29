<?php

namespace Luchki\YoutrackSDK\Contracts;

use Doctrine\Common\Collections\ArrayCollection;

interface IIssue
{

        public function setID(?string $id): void;

        public function getID(): ?string;

        public function getSummary(): string;

        public function getDescription(): ?string;

        public function setFieldValue(string $field_name, mixed $value): void;
        /**
         * @return array<ICustomField>
         */
        public function getCustomFields(): array;

        public function setAvailableCustomFields(ArrayCollection $available_custom_fields): void;
}