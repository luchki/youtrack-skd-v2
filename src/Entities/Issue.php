<?php

namespace Luchki\YoutrackSDK\Entities;

use Luchki\YoutrackSDK\Contracts\ICustomField;
use Luchki\YoutrackSDK\Contracts\IIssue;
use Luchki\YoutrackSDK\Exceptions\InvalidValueException;
use Luchki\YoutrackSDK\Factories\CustomFieldFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

class Issue implements IIssue
{
        private string $summary;
        private ?string $description;
        /** @var array<ICustomField> */
        private array $custom_fields = [];
        private ArrayCollection $available_custom_fields;
        private ?string $id;

        public function __construct(
                string  $summary,
                ?string $description,
                ?string $id = null
        ) {
                $this->setSummary($summary);
                $this->setDescription($description);
                $this->setID($id);
        }

        /**
         * @return string
         */
        public function getSummary(): string {
                return $this->summary;
        }

        /**
         * @param string $summary
         */
        private function setSummary(string $summary): void {
                $this->summary = $summary;
        }

        /**
         * @return string|null
         */
        public function getDescription(): ?string {
                return $this->description;
        }

        /**
         * @param string|null $description
         */
        private function setDescription(?string $description): void {
                $this->description = $description;
        }

        public function setFieldValue(string $field_name, mixed $value): void {
                $this->validateField($field_name);
                $this->validateValue($field_name, $value);


                $field_type = $this->getFieldType($field_name);

                try {
                        $field = CustomFieldFactory::makeFieldFromArray($field_name, $field_type, $value);
                } catch (\Exception $e) {
                        throw new InvalidValueException("You need to pass value with type of {$e->getMessage()}"
                                . " for field \"{$field_name}\"");
                }

                $this->addOrReplaceCustomField($field);
        }

        /**
         * @param ICustomField $custom_field
         */
        private function addOrReplaceCustomField(ICustomField $custom_field): void {
                $found = false;
                foreach ($this->custom_fields as $key => $cf) {
                        if ($cf->getName() === $custom_field->getName()) {
                                $found = true;
                                $this->custom_fields[$key] = $custom_field;
                                break;
                        }
                }

                if (!$found) {
                        $this->custom_fields[] = $custom_field;
                }
        }

        public function getCustomFields(): array {
                return $this->custom_fields;
        }

        /**
         * @return string|null
         */
        public function getID(): ?string {
                return $this->id;
        }

        /**
         * @param string|null $id
         */
        public function setID(?string $id): void {
                $this->id = $id;
        }

        public function setAvailableCustomFields(ArrayCollection $available_custom_fields): void {
                $this->available_custom_fields = $available_custom_fields;
        }

        private function fieldExists(string $field_name): bool {
                return !$this->getAvailableCustomField($field_name)->isEmpty();
        }

        private function getAvailableCustomField(string $field_name): ArrayCollection {
                $expr = new Comparison('name', '=', $field_name);
                $criteria = new Criteria();
                $criteria->where($expr);

                return $this->available_custom_fields->matching($criteria);
        }

        private function getFieldType(string $field_name): string {
                $expr = new Comparison('name', '=', $field_name);
                $criteria = new Criteria();
                $criteria->where($expr);

                return $this->available_custom_fields->matching($criteria)->current()['type'];
        }

        private function getAvailableFieldNames(): array {
                $out = [];
                foreach ($this->available_custom_fields as $custom_field) {
                        $out[] = $custom_field['name'];
                }

                return $out;
        }

        /**
         * @return string
         */
        private function getListOfAvailableFields(): string {
                $available_fields = $this->getAvailableFieldNames();
                return implode(', ', $available_fields);
        }

        private function valueIsAvailable(mixed $available_field, mixed $value): bool {
                $valid = true;
                if (
                        array_key_exists('available_values', $available_field)
                        && !empty($available_field['available_values'])
                ) {
                        $valid = false;
                        if (is_array($value)) {
                                $values_to_check = $value;
                                foreach ($values_to_check as $key => $value_to_check) {
                                        foreach ($available_field['available_values'] as $available_value) {
                                                if ($available_value === $value_to_check) {
                                                        unset($values_to_check[$key]);
                                                }
                                        }
                                }

                                if (empty($values_to_check)) {
                                        $valid = true;
                                }
                        } else {
                                foreach ($available_field['available_values'] as $available_value) {
                                        if ($available_value === $value) {
                                                $valid = true;
                                                break;
                                        }
                                }
                        }
                }

                return $valid;
        }

        /**
         * @param $values
         * @return string
         */
        private function getAvailableValuesText($values): string {
                return implode(', ', $values);
        }

        /**
         * @param string $field_name
         * @throws \Exception
         */
        private function validateField(string $field_name): void {
                $available_fields_txt = $this->getListOfAvailableFields();
                if (!$this->fieldExists($field_name)) {
                        throw new \Exception("Field with name {$field_name} not found in project."
                                . "List of available fields: {$available_fields_txt}");
                }
        }

        /**
         * @param string $field_name
         * @param mixed  $value
         * @throws \Exception
         */
        private function validateValue(string $field_name, mixed $value): void {
                $available_field = $this->getAvailableCustomField($field_name)->current();
                if (!$this->valueIsAvailable($available_field, $value)) {
                        $value_txt = is_array($value)
                                ? implode(', ', $value)
                                : $value;
                        $available_values_txt = $this->getAvailableValuesText($available_field['available_values']);
                        throw new \Exception("Can't set value \"{$value_txt}\" to field \"{$field_name}\". 
                                List of available values: {$available_values_txt}
                        ");
                }
        }
}