<?php declare(strict_types = 1);

namespace RemoteDataBlocks\Example\GitHub;

use RemoteDataBlocks\Config\QueryContext\HttpQueryContext;
use RemoteDataBlocks\Integrations\GitHub\GitHubDataSource;
class GitHubGetFileAsHtmlQuery extends HttpQueryContext {
	public function get_input_schema(): array {
		return [
			'file_path' => [
				'name' => 'File Path',
				'type' => 'string',
			],
			'sha'       => [
				'name' => 'SHA',
				'type' => 'string',
			],
			'size'      => [
				'name' => 'Size',
				'type' => 'number',
			],
			'url'       => [
				'name' => 'URL',
				'type' => 'string',
			],
		];
	}

	public function get_output_schema(): array {
		return [
			'is_collection' => false,
			'mappings'      => [
				'file_content' => [
					'name' => 'File Content',
					'path' => '$.content',
					'type' => 'html',
				],
				'file_path'    => [
					'name' => 'File Path',
					'path' => '$.path',
					'type' => 'string',
				],
				'sha'          => [
					'name' => 'SHA',
					'path' => '$.sha',
					'type' => 'string',
				],
				'size'         => [
					'name' => 'Size',
					'path' => '$.size',
					'type' => 'number',
				],
				'url'          => [
					'name' => 'URL',
					'path' => '$.url',
					'type' => 'string',
				],
			],
		];
	}

	public function get_endpoint( array $input_variables ): string {
		/** @var GitHubDataSource $data_source */
		$data_source = $this->get_data_source();

		return sprintf(
			'https://api.github.com/repos/%s/%s/contents/%s?ref=%s',
			$data_source->get_repo_owner(),
			$data_source->get_repo_name(),
			$input_variables['file_path'],
			$data_source->get_ref()
		);
	}

	public function get_request_headers( array $input_variables ): array {
		return [
			'Accept' => 'application/vnd.github.html+json',
		];
	}

	public function process_response( string $html_response_data, array $input_variables ): array {
		return [
			'content'   => $html_response_data,
			'file_path' => $input_variables['file_path'],
			'sha'       => $input_variables['sha'],
			'size'      => $input_variables['size'],
			'url'       => $input_variables['url'],
		];
	}
}
