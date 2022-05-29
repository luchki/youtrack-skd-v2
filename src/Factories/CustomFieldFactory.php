<?php

namespace Luchki\YoutrackSDK\Factories;

use Luchki\YoutrackSDK\Contracts\ICustomField;
use Luchki\YoutrackSDK\Entities\CustomFields\EnumField;
use Luchki\YoutrackSDK\Entities\CustomFields\MultiEnumField;
use Luchki\YoutrackSDK\Entities\CustomFields\SimpleTextField;
use Luchki\YoutrackSDK\Entities\CustomFields\StateField;
use Luchki\YoutrackSDK\Entities\CustomFields\TextField;
use Luchki\YoutrackSDK\Entities\CustomFields\UserField;

class CustomFieldFactory
{
        public static function makeFieldFromArray(
                string $name,
                string $type,
                mixed  $value
        ): ICustomField {
                $field = match ($type) {
                        'SimpleIssueCustomField' => new SimpleTextField($name, $type, $value),
                        'SingleEnumIssueCustomField' => new EnumField($name, $type, $value),
                        'StateIssueCustomField' => new StateField($name, $type, $value),
                        'MultiEnumIssueCustomField' => new MultiEnumField($name, $type, $value),
                        'SingleUserIssueCustomField' => new UserField($name, $type, $value),
                        'TextIssueCustomField' => new TextField($name, $type, $value),
                        default => throw new \Exception("I don't know about type {$type}"),
                };

                return $field;
        }
}