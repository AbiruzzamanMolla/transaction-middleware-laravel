<?php

namespace azmolla\TransactionMiddleware\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * For DELETE requests, start a DB transaction.
     *
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @throws \Throwable
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('delete')) {
            DB::beginTransaction();

            try {
                $response = $next($request);
                DB::commit();
                return $response;
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }

        // If not a DELETE request, just continue the pipeline.
        return $next($request);
    }
}
