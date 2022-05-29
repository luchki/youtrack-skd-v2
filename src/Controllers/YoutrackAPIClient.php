<?php
namespace Luchki\YoutrackSDK\Controllers;

use Luchki\YoutrackSDK\Contracts\IIssue;
use Luchki\YoutrackSDK\Contracts\IProjectInfo;
use Luchki\YoutrackSDK\Contracts\ITokenAuthentication;
use Luchki\YoutrackSDK\Contracts\IYoutrackAPI;
use Luchki\YoutrackSDK\Entities\Issue;
use Luchki\YoutrackSDK\Entities\ProjectInfo;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class YoutrackAPIClient extends Client implements IYoutrackAPI
{
        private ITokenAuthentication $authentication;
        private string $youtrack_base_url;

        public function __construct(
                ITokenAuthentication $authentication,
                string $youtrack_base_url,
                array $config = []
        ) {
                $auth_config = [
                        'headers' => [
                                'Accept' => 'application/json',
                                'Authorization' => "Bearer {$authentication->getToken()}",
                                'Cache-Control' => 'no-cache',
                                'Content-Type' => 'application/json',
                        ],
                        'base_uri' => $authentication->getApiURL(),
                ];
                $result_config = array_merge_recursive($auth_config, $config);

                parent::__construct($result_config);
                $this->authentication = $authentication;
                $this->youtrack_base_url = $youtrack_base_url;
        }

        public function getAllProjects(array $fields = []): array {
                $response = $this->request('GET', 'admin/projects', [
                        'query' =>
                                [
                                        'fields' => 'id,name',
                                ],
                ]);


                return json_decode($response->getBody(), true);
        }

        public function getProjectInfoByName(string $string): ?IProjectInfo {
                $response = $this->request('GET', 'admin/projects', [
                        'query' =>
                                [
                                        'fields' => 'id,name,shortName',
                                        'query' => $string,
                                ],
                ]);


                $project_data = current(json_decode($response->getBody(), true));
                if (!empty($project_data)) {
                        $project = (new ProjectInfo($project_data['id'], $project_data['name']));
                }

                return $project ?? null;
        }

        public function createIssue(IProjectInfo $project, IIssue $new_issue): Issue {

                $custom_fields_post_array = [];
                foreach ($new_issue->getCustomFields() as $custom_field) {
                        $custom_fields_post_array[] = [
                                'name' => $custom_field->getName(),
                                '$type' => $custom_field->getType(),
                                'value' => $custom_field->getValue()
                        ];
                }


                $post_array = [
                        'project' => ['id' => $project->getID()],
                        'summary' => $new_issue->getSummary(),
                        'description' => $new_issue->getDescription(),
                        'customFields' => $custom_fields_post_array
                ];

                $response = $this->post('issues', [
                        RequestOptions::JSON => $post_array,
                ]);

                $issue_id = json_decode($response->getBody(), true)['id'];

                $new_issue->setID($issue_id);

                return $new_issue;
        }

        public function getProjectCustomFields(string $project_name): array {
                $response = $this->request('GET', "issues",
                        ['query' =>
                                [

                                        'fields' => 'name,customFields(id,name,$type,value(name,login,text))',
                                        'query' => "project: {$project_name}",
                                        '$top' => 1
                                ],
                        ]
                );

                $response_array = current(json_decode($response->getBody(), true))['customFields'];

                return $response_array;
        }

        public function getEnumAvailableValues(string $project_id, string $enum_field_id): array {

//                enum?fields=name,id,values(name,id,description,ordinal),isUpdateable&$top=2


                $result = $this->request(
                        'get',
                        "admin/projects/{$project_id}/customFields/{$enum_field_id}/bundle/",
                        [
                                'query' => [
                                        'values',
                                        'fields' => 'id,name,values(name,id,description,ordinal)'
                                ]
                        ]
                );
                return json_decode($result->getBody(),true)['values'];
        }

        public function getAPIUrl(): string {
                return $this->authentication->getApiURL();
        }

        public function getBaseUrl(): string {
                return $this->youtrack_base_url;
        }
}