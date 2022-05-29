<?php

namespace Luchki\YoutrackSDK\Controllers;

use Luchki\YoutrackSDK\Contracts\IIssue;
use Luchki\YoutrackSDK\Contracts\IProjectInfo;
use Luchki\YoutrackSDK\Contracts\IYoutrackAPI;
use GuzzleHttp\RequestOptions;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractProject
{
        private IYoutrackAPI $api;
        private IProjectInfo $project_info;
        private ArrayCollection $available_custom_fields;

        public function __construct(IYoutrackAPI $api, string $project_name) {
                $this->api = $api;
                $this->init($project_name);
        }

        /**
         * @return IYoutrackAPI
         */
        protected function getApi(): IYoutrackAPI {
                return $this->api;
        }

        protected function init(string $project_name): void {
                $this->loadProjectInfo($project_name);
        }

        public function loadAvailableCustomFields(): void {
                $this->available_custom_fields = new ArrayCollection();

                $custom_fields_array = $this->getApi()->getProjectCustomFields($this->getProjectInfo()->getName());

                foreach ($custom_fields_array as $field) {
                        $id = $field['id'];
                        $available_values = null;
                        if (
                                $field['$type'] === 'SingleEnumIssueCustomField'
                                || $field['$type'] === 'MultiEnumIssueCustomField'
                                || $field['$type'] === 'StateIssueCustomField'
                                || $field['$type'] === 'SingleUserIssueCustomField'
                        ) {
                                $values = $this->getApi()->getEnumAvailableValues(
                                        $this->getProjectInfo()->getID(),
                                        $field['id']
                                );



                                foreach ($values as $value) {
                                        $available_values[] = $value['name'];
                                }
                        }

                        $this->getAvailableCustomFields()->add([
                                'id' => $id,
                                'name' => $field['name'],
                                'type' => $field['$type'],
                                'available_values' => $available_values ?? null,
                        ]);

                }
        }

        /**
         * @param string $project_name
         * @throws \Exception
         */
        private function loadProjectInfo(string $project_name): void {
                $project_info = $this->getApi()->getProjectInfoByName($project_name);
                if ($project_info === null) {
                        throw new \Exception("Didn't found any projects with name {$project_name}");
                }

                $this->project_info = $project_info;
        }

        /**
         * @return IProjectInfo
         */
        public function getProjectInfo(): IProjectInfo {
                return $this->project_info;
        }

        public function saveIssue(IIssue $issue): void {
                $custom_fields_post_array = [];
                foreach ($issue->getCustomFields() as $custom_field) {
                        $custom_fields_post_array[] = $custom_field->toArray();
                }

                $post_array = [
                        'project' => ['id' => $this->getProjectInfo()->getID()],
                        'summary' => $issue->getSummary(),
                        'description' => $issue->getDescription(),
                        'customFields' => $custom_fields_post_array
                ];

                $response = $this->getApi()->request('POST', 'issues', [
                        RequestOptions::JSON => $post_array,
                ]);

                $issue_id = json_decode($response->getBody(), true)['id'];

                $issue->setID($issue_id);
        }

        abstract protected function makeIssueObject(string $title, ?string $description = null): IIssue;

        final public function createIssue(string $title, ?string $description = null): IIssue {
                $issue = $this->makeIssueObject($title, $description);
                $issue->setAvailableCustomFields($this->getAvailableCustomFields());

                return $issue;
        }

        /**
         * @return ArrayCollection
         */
        public function getAvailableCustomFields(): ArrayCollection {
                if (!isset($this->available_custom_fields)) {
                        $this->loadAvailableCustomFields();
                }
                return $this->available_custom_fields;
        }

        public function getLinkToIssue(string $id): string {
                $url = $this->api->getBaseUrl();

                return "{$url}issue/{$id}";
        }

        public function findIssueByField(string $field_name, string $value): ?array {
                $array = json_decode($this->api->request(
                        "get",
                        'issues',
                        [
                                'query' => [
                                        'fields' => 'id',
                                        'query' => "project: {$this->getProjectInfo()->getName()} {$field_name}: {$value}",
                                ]
                        ]
                )->getBody(), true);
                return is_array(current($array))
                        ? current($array)
                        : null;
        }
}