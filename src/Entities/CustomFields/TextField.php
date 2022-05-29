<?php

namespace Luchki\YoutrackSDK\Entities\CustomFields;

use Luchki\YoutrackSDK\Contracts\IFieldValueHolder;
use Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders\TextFieldValueHolder;

class TextField extends CustomField
{

        protected function makeValueHolder(string $field_type, mixed $value): IFieldValueHolder {
                $value_holder = new TextFieldValueHolder('TextFieldValue');
                $value_holder->setValue($value);

                return $value_holder;
        }
}