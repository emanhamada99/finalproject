<?php
namespace App\Providers;

use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Overload authorization method from \Laravel\Horizon\HorizonApplicationServiceProvider
     * to allow access to Horizon without having a logged in user.
     *
     * @return void
     */
    protected function authorization()
    {
        Horizon::auth(function ($request) {
            return true;
        });
    }
}

return [

    // ...

    'basic_auth' => [
        'username' => env('HORIZON_BASIC_AUTH_USERNAME'),
        'password' => env('HORIZON_BASIC_AUTH_PASSWORD'),
    ],

];


namespace App\Http\Middleware;

use Closure;

class HorizonBasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authenticationHasPassed = false;

        if ($request->header('PHP_AUTH_USER', null) && $request->header('PHP_AUTH_PW', null)) {
            $username = $request->header('PHP_AUTH_USER');
            $password = $request->header('PHP_AUTH_PW');

            if ($username === config('horizon.basic_auth.username') && $password === config('horizon.basic_auth.password')) {
                $authenticationHasPassed = true;
            }
        }

        if ($authenticationHasPassed === false) {
            return response()->make('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
        }

        return $next($request);
    }
}

 'middleware' => ['web', 'horizonBasicAuth'],


 




?>