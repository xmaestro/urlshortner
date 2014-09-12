<?php

namespace Xmaestro\Urlshortner;

use Illuminate\Support\ServiceProvider;
use \Artisan;

class UrlshortnerServiceProvider extends ServiceProvider {
	
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
	public function boot() {

		$this->package ( 'xmaestro/urlshortner' );
	
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app ['Urlshortner'] = $this->app->share ( function () {
			
			return new Urlshortner;
		
		} );
		
		$Urlshortner = $this->app ['Urlshortner'];
		
		$Urlshortner::reRoute ();
	
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return array ('Urlshortner' );
	}

}
