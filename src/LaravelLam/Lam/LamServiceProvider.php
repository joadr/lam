<?php namespace LaravelLam\Lam;

use Illuminate\Support\ServiceProvider;
use LaravelLam\Lam\Commands\UpdateCommand;
use LaravelLam\Lam\Commands\InstallCommand;
use LaravelLam\Lam\Commands\CreateThemeCommand;
use Route;

class LamServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('laravel-lam/lam', 'lam', base_path() . '/lam');
        $this->commands('lam.command.update');
        $this->commands('lam.command.install');
        $this->commands('lam.command.create-theme');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('lam.command.update', function()
        {
            return new UpdateCommand;
        });

        $this->app->bind('lam.command.install', function()
        {
            return new InstallCommand;
        });

        $this->app->bind('lam.command.create-theme', function()
        {
            return new CreateThemeCommand;
        });

        Route::get('lam/assets/{creator}/{name}/{path1}/{path2?}/{path3?}/{path4?}/{path5?}/{path6?}/{path7?}',
            'LaravelLam\Lam\Controllers\PublicAssetsController@show');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
