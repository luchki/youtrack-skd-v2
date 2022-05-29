<?php

use Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders\ArrayWithNameAndTypeValueHolder;
use Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders\MultiEnumValueHolder;
use Codeception\Test\Unit;

class MultiEnumValueHolderTest extends Unit
{
        public function testSetValue() {
                $value_holder = (new MultiEnumValueHolder('MultiEnumIssueCustomField'));

                $value = [
                        'babas',
                        'bubus',
                        'bibis'
                ];

                $value_holder->setValue(
                        $value
                );

                $this->assertEquals($value, $value_holder->getValue());

                $result_value_for_api = [
                        ['name' => 'babas', '$type' => 'EnumBundleElement'],
                        ['name' => 'bubus', '$type' => 'EnumBundleElement'],
                        ['name' => 'bibis', '$type' => 'EnumBundleElement'],
                ];
                $this->assertEquals($result_value_for_api, $value_holder->getValueForApi());
        }
}