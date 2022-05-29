<?php

use Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders\ArrayWithNameAndTypeValueHolder;
use Luchki\YoutrackSDK\Exceptions\InvalidValueException;
use Codeception\Test\Unit;

class ArrayWithNameAndTypeValueHolderTest extends Unit
{
        public function testArrayWithNameAndTypeValueHolder() {
                $value_holder = (new ArrayWithNameAndTypeValueHolder('EnumBundleElement'));
                $some_text = 'some_text';
                $value_holder->setValue($some_text);
                $this->assertEquals($some_text, $value_holder->getValue());
                $this->assertEquals(
                        [
                                'name' => $some_text,
                                '$type' => 'EnumBundleElement'
                        ],
                        $value_holder->getValueForApi());

        }

        public function testBadValue() {
                $value_holder = (new ArrayWithNameAndTypeValueHolder('EnumBundleElement'));
                $this->expectException(InvalidValueException::class);
                $not_text = ['arr', 'bbrr'];
                $value_holder->setValue($not_text);

        }
}