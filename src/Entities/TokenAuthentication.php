<?php
namespace Luchki\YoutrackSDK\Entities;

use Luchki\YoutrackSDK\Contracts\ITokenAuthentication;

class TokenAuthentication implements ITokenAuthentication
{
        /** @var string */
        private $api_url;
        /** @var string */
        private $token;

        public function __construct(string $api_url, string $token) {
                $this->setApiUrl($api_url);
                $this->setToken($token);
        }


        public function getApiUrl(): string {
                return $this->api_url;
        }

        private function setApiUrl(string $api_url): void {
                $this->api_url = $api_url;
        }

        public function getToken(): string {
                return $this->token;
        }

        private function setToken(string $token): void {
                $this->token = $token;
        }
}