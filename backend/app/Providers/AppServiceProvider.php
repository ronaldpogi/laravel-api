<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data = [], $message = null, $status = 200) {

            $defaultMessage = $message ?? __('success.request_processed');

            $response = ['status' => true, 'message' => $defaultMessage];

            if ($data instanceof AnonymousResourceCollection) {
                // If it's a paginated collection
                if ($data->resource instanceof LengthAwarePaginator) {
                    $paginator = $data->resource;

                    $response['data']       = $data->collection; // already mapped
                    $response['pagination'] = [
                        'total'        => $paginator->total(),
                        'per_page'     => $paginator->perPage(),
                        'current_page' => $paginator->currentPage(),
                        'last_page'    => $paginator->lastPage(),
                        'from'         => $paginator->firstItem(),
                        'to'           => $paginator->lastItem(),
                    ];
                } else {
                    $response['data'] = $data->collection; // for non-paginated collection
                }
            } else {
                $response['data'] = $data; // plain data
            }

            return response()->json($response, $status);

        });

        Response::macro('error', function ($message = null, $status = 400, $errors = []) {
            $defaultMessage = $message ?? __('errors.bad_request');

            $response = ['status' => false, 'message' => $defaultMessage, $errors];

            return response()->json($response, $status);
        });
    }
}
