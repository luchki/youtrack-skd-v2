<?php

use Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders\SimpleTextValueHolder;
use Luchki\YoutrackSDK\Exceptions\InvalidValueException;
use Codeception\Test\Unit;

class SimpleFieldValueHolderTest extends Unit
{
        public function testSimpleTextValueHolder() {
                $value_holder = (new SimpleTextValueHolder('string'));
                $some_text = 'some_text';
                $value_holder->setValue($some_text);
                $this->assertEquals($some_text, $value_holder->getValue());
                $this->assertEquals($some_text, $value_holder->getValueForApi());

                $this->expectException(InvalidValueException::class);
                $not_text = ['arr', 'bbrr'];
                $value_holder->setValue($not_text);
        }
}