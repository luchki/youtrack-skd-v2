<?php

namespace Luchki\YoutrackSDK\Entities;

use Luchki\YoutrackSDK\Contracts\IProjectInfo;

class ProjectInfo implements IProjectInfo
{
        private string $id;
        private string $name;

        public function __construct(string $id, string $name) {
                $this->setId($id);
                $this->setName($name);
        }

        /**
         * @return string
         */
        public function getId(): string {
                return $this->id;
        }

        /**
         * @param string $id
         */
        private function setId(string $id): void {
                $this->id = $id;
        }

        /**
         * @return string
         */
        public function getName(): string {
                return $this->name;
        }

        /**
         * @param string $name
         */
        private function setName(string $name): void {
                $this->name = $name;
        }
}