# Data source

A data source defines basic reusable properties of an API and is required to define a [query](query.md).

## DataSourceInterface

At its simplest, a data source implements `DataSourceInterface` and describes itself with the following methods:

- `get_display_name(): string`: Return the display name of the data source.
- `get_image_url(): string|null`: Optionally, return an image URL that can represent the data source in UI.

## HttpDataSource

The `HttpDataSource` class implements `DataSourceInterface` and provides common reusable properties of an HTTP API:

- `get_endpoint(): string`: Returns the base URL of the API endpoint. This can be overridden by a query.
- `get_request_headers(): array`: Returns an associative array of HTTP headers to be sent with each request. This is a common place to set authentication headers such as `Authorization`. This array can be extended or overridden by a query.

## Example

Most HTTP-powered APIs can be represented by defining a class that extends `HttpDataSource`. Here's an example of a data source for US ZIP code data:

```php
class ZipCodeDataSource extends HttpDataSource {
	public function get_display_name(): string {
		return 'US ZIP codes';
	}

	public function get_endpoint(): string {
		return 'https://api.zippopotam.us/us/';
	}

	public function get_request_headers(): array {
		return [
			'Content-Type' => 'application/json',
		];
	}
}
```

The logic to fetch data from the API is defined in a [query](query.md).

## Custom data source

APIs that do not use HTTP as transport may require a custom data source. Implement `DataSourceInterface` and provide methods that define reusable properties of your API. The actual implementation of your transport will likely be provided via a [custom query runner](./query-runner.md).

Here is an example of a data source for a WebDAV server:

```php
class WebDavFilesDataSource implements DataSourceInterface {
	public function get_display_name(): string {
		return 'WebDAV Files';
	}

	public function get_image_url(): null {
		return null;
	}

	public function get_webdav_root(): string {
		return 'webdavs://webdav.example.com/';
	}
}
```
