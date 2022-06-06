<?php

namespace Luchki\YoutrackSDK\Contracts;

use GuzzleHttp\ClientInterface;

interface IYoutrackAPI extends ClientInterface
{
        public function getProjectInfoByName(string $string): ?IProjectInfo;


        public function getEnumAvailableValues(string $project_name, string $enum_field_id): array;

        public function getAPIUrl(): string;

        public function getBaseUrl(): string;
}