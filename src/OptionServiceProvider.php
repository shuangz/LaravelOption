<?php

namespace Shuangz\Option;

use Illuminate\Support\ServiceProvider;

class OptionServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->singleton('option', function () {
			return new OptionRepository;
		});

		
	}
}
