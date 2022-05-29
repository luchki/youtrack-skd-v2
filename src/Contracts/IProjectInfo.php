<?php

namespace Luchki\YoutrackSDK\Contracts;

interface IProjectInfo
{
        public function getID(): string;

        public function getName(): string;
}