<?php

use Luchki\YoutrackSDK\Factories\CustomFieldFactory;
use Codeception\TestCase\Test;

class CustomFieldFactoryTest extends Test
{
        public function dataProvider() {
                return [
                        [
                                'in' => [
                                        'name' => 'OrderNumber',
                                        'type' => 'SimpleIssueCustomField',
                                        'value' => 'asdfasdfasdf',
                                ],
                                'out' => [
                                        'value' => 'asdfasdfasdf',
                                        '$type' => 'SimpleIssueCustomField',
                                        'name' => 'OrderNumber',
                                ]
                        ],
                        [
                                'in' => [
                                        'name' => 'Employee',
                                        'type' => 'SingleEnumIssueCustomField',
                                        'value' => 'JohnDoe',
                                ],
                                'out' => [
                                        'name' => 'Employee',
                                        '$type' => 'SingleEnumIssueCustomField',
                                        'value' => [
                                                'name' => 'JohnDoe',
                                                '$type' => 'EnumBundleElement'
                                        ],
                                ],
                        ],
                        [
                                'in' => [
                                        'name' => 'State',
                                        'type' => 'StateIssueCustomField',
                                        'value' => 'In Progress',
                                ],
                                'out' => [
                                        'name' => 'State',
                                        '$type' => 'StateIssueCustomField',
                                        'value' => [
                                                'name' => 'In Progress',
                                                '$type' => 'StateBundleElement'
                                        ],
                                ],
                        ],
                        [
                                'in' => [
                                        'name' => 'links list',
                                        'type' => 'MultiEnumIssueCustomField',
                                        'value' => ['link1', 'link2'],
                                ],
                                'out' => [
                                        'name' => 'links list',
                                        '$type' => 'MultiEnumIssueCustomField',
                                        'value' => [
                                                [
                                                        'name' => 'link1',
                                                        '$type' => 'EnumBundleElement'
                                                ],
                                                [
                                                        'name' => 'link2',
                                                        '$type' => 'EnumBundleElement'
                                                ],
                                        ],
                                ],
                        ],
                        [
                                'in' => [
                                        'name' => 'Assignee',
                                        'type' => 'SingleUserIssueCustomField',
                                        'value' => [
                                                'login' => 'john_doe',
                                                'name' => 'John Doe'
                                        ],
                                ],
                                'out' => [
                                        'name' => 'Assignee',
                                        '$type' => 'SingleUserIssueCustomField',
                                        'value' => [
                                                'name' => 'John Doe',
                                                'login' => 'john_doe',
                                                '$type' => 'User'
                                        ],
                                ],
                        ],
                        [
                                'in' => [
                                        'name' => 'Text from customer',
                                        'type' => 'TextIssueCustomField',
                                        'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis',
                                ],
                                'out' => [
                                        'name' => 'Text from customer',
                                        '$type' => 'TextIssueCustomField',
                                        'value' => [
                                                'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis',
                                                '$type' => 'TextFieldValue'
                                        ],
                                ],
                        ],
                ];
        }

        /**
         * @dataProvider dataProvider
         */
        public function testFields(array $in, array $out) {
                $name = $in['name'];
                $type = $in['type'];
                $value = $in['value'];
                $field = CustomFieldFactory::makeFieldFromArray(
                        $name,
                        $type,
                        $value,
                );
                $this->assertEquals($value, $field->getValue());

                $result_array = $out;
                $this->assertEquals($result_array, $field->toArray());
        }

//        public function testSingleEnumIssueCustomField() {
//                $value = 'JohnDoe';
//                $name = 'Employee';
//                $type = 'SingleEnumIssueCustomField';
//
//                $field = CustomFieldFactory::makeFieldFromArray(
//                        $name,
//                        $type,
//                        $value
//                );
//
//                $this->assertEquals($value, $field->getValue());
//
//                $result_array = [
//                        'name' => $name,
//                        '$type' => $type,
//                        'value' => [
//                                'name' => $value,
//                                '$type' => 'EnumBundleElement'
//                        ]
//                ];
//                $this->assertEquals($result_array, $field->toArray());
//        }
//
//        public function testSingleEnumIssueCustomField() {
//                $value = 'JohnDoe';
//                $name = 'Employee';
//                $type = 'SingleEnumIssueCustomField';
//
//                $field = CustomFieldFactory::makeFieldFromArray(
//                        $name,
//                        $type,
//                        $value
//                );
//
//                $this->assertEquals($value, $field->getValue());
//
//                $result_array = [
//                        'name' => $name,
//                        '$type' => $type,
//                        'value' => [
//                                'name' => $value,
//                                '$type' => 'EnumBundleElement'
//                        ]
//                ];
//                $this->assertEquals($result_array, $field->toArray());
//        }
}