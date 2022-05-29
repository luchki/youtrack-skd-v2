<?php

use Luchki\YoutrackSDK\Entities\CustomFields\ValueHolders\UserValueHolder;
use Luchki\YoutrackSDK\Exceptions\InvalidValueException;
use Codeception\Test\Unit;

class UserValueHolderTest extends Unit
{
        public function testUserValueHolder() {
                $value_holder = (new UserValueHolder('User'));
                $value = [
                        'login' => 'user_login',
                        'name' => 'UserName',
                ];
                $value_holder->setValue($value);
                $this->assertEquals($value, $value_holder->getValue());
                $this->assertEquals(
                        [
                                'login' => 'user_login',
                                'name' => 'UserName',
                                '$type' => 'User'
                        ],
                        $value_holder->getValueForApi());

                $this->expectException(InvalidValueException::class);
                $not_array = 'blwe';
                $value_holder->setValue($not_array);
        }

        public function testSetBadValueToUserValueHolder() {
                $value_holder = (new UserValueHolder('User'));
                $this->expectException(InvalidValueException::class);
                $not_valid_array = [
                        'asdfasf' => 'asdfasdf'
                ];
                $value_holder->setValue($not_valid_array);
        }
}