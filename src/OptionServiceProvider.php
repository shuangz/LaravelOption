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
		
		// OptionModel::created(function ($option) {
  //           //$option->forgetCached();
  //       });

  //       OptionModel::updated(function ($option) {
  //           \Log::info($option." updated");
  //       });

  //       OptionModel::deleted(function ($option) {
  //           //$option->forgetCached();
  //       });

  //       OptionModel::saved(function ($option) {
  //       	\Log::info($option);
  //           //$option->forgetCached();
  //       });
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
