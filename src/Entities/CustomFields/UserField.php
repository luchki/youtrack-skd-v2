<?php

namespace Luchki\YoutrackSDK\Entities\CustomFields;

use Luchki\YoutrackSDK\Contracts\IFieldValueHolder;
use Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders\UserValueHolder;

class UserField extends CustomField
{
        protected function makeValueHolder(string $field_type, mixed $value): IFieldValueHolder {
                $value_holder = new UserValueHolder('User');
                $value_holder->setValue($value);

                return $value_holder;
        }
}