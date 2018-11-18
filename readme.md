# ApiTemplate

Make life easier

## Installation

- install jwt for laravel Via Composer
``` bash
$ composer require tymon/jwt-auth 1.0.0-rc.3
```

- Configure Auth guard change this lines in "config/auth.php"
``` bash 
guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
	    ],
```

- add to auth config "config/auth.php"
``` php
    /*
    |--------------------------------------------------------------------------
    | API Client Credentials
    |--------------------------------------------------------------------------
    |
    */

    'client_id' => env('CLIENT_ID', ''),
    'client_secret' => env('CLIENT_SECRET'. ''),
```

- Update your User model to implements "Tymon\JWTAuth\Contracts\JWTSubject"
``` php
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
```

- install our package Via Composer

``` bash
$ composer require --dev bor3y/apitemplate
```

- publish package using command
``` bash
$ php artisan auth:api:publish
```

- Add namespace to apiRoutes in "app/Providers/RouteServiceProvider.php"
``` php
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace . '\API')
              ->as('api.')
             ->group(base_path('routes/api.php'));
    }
```

- Add basic authentication routes 
``` php
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){
        Route::group(['middleware' => 'auth.api.public'], function() {
            Route::post('/register', 'AuthController@register')->name('register');
            Route::post('/login', 'AuthController@login')->name('login');
            Route::group(['prefix' => 'password', 'as' => 'password.'], function(){
                Route::post('/forget', 'PasswordController@sendResetLinkEmail')->name('forget');
            });
            
            Route::post('/token/refresh', 'AuthController@refreshToken')->name('refreshToken');
        });
    
        Route::group(['middleware' => 'auth:api'], function(){
            Route::get('/user', ['as' => 'user', 'uses' => 'AuthController@user']);
            Route::post('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
            Route::post('/password/change', 'PasswordController@changePassword')->name('password.change');
        });
    });
```

- add to kernel routesMiddleware "app/Http/Kernel.php"
``` php
    'auth.api.public' => \App\Http\Middleware\AuthorizePublicApiRequests::class
```

We are done

## License

license. Please see the [license file](license.md) for more information.
