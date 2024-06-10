<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
	/**
	 * Return a success JSON response.
	 *
	 * @param  array|string  $data
	 * @param  string  $message
	 * @param  int|null  $code
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function success($data, string $message = null, int $code = 200)
	{
		if (empty($message))
			return response()->json([
				'status' => 'success',
				'data' => $data
			], $code);
		else
			return response()->json([
				'status' => 'success',
				'message' => $message,
				'data' => $data
			], $code);
	}

	/**
	 * Return an error JSON response.
	 *
	 * @param  string  $message
	 * @param  int  $code
	 * @param  array|string|null  $data
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function error(string $message = null, int $code, $data = null)
	{
		return response()->json([
			'status' => 'error',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function filter($query)
	{
		$this->getFilters()
			->each(function ($value, $filter) use ($query) {
				if ($value === 'true') {
					$value = true;
				} elseif ($value === 'false') {
					$value = false;
				}

				if (Str::contains($filter, ':')) {
					[$field, $condition] = explode(':', $filter);
				} else {
					$field = $filter;
					$condition = 'equals';
				}

				$this->queryCondition($query, $field, $condition, $value);
			});

		return $this;
	}

	protected function doesntHaveFilter($field)
	{
		return !collect(request()->filter ?? [])
			->map(function ($value, $param) {
				return explode(':', $param)[0];
			})
			->contains($field);
	}

	protected function sort($query)
	{
		if (!$sorts = request()->sort) {
			return $this;
		}

		collect(explode(',', $sorts))
			->each(function ($sort) use ($query) {
				$order = 'asc';

				if (Str::startsWith($sort, '-')) {
					$sort = substr($sort, 1);
					$order = 'desc';
				}

				$query->orderBy($sort, $order);
			});

		return $this;
	}

	protected function paginate($query)
	{
		$columns = explode(',', request()->input('fields', '*'));

		return $query
			->paginate(request()->input('limit', config('statamic.api.pagination_size')), $columns)
			->appends(request()->only(['filter', 'limit', 'page', 'sort']));
	}

	protected function queryParam($key, $default = null)
	{
		if ($key === 'shop') {
			return request()->input('shop', current_shop_id());
		}

		if ($key === 'fields') {
			return explode(',', request()->input($key, '*'));
		}

		return request()->input($key, $default);
	}
}
