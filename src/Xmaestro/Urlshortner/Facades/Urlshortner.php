<?php

namespace Xmaestro\Urlshortner\Facades;

use Illuminate\Support\Facades\Facade;

class Urlshortner extends Facade {
	
	// Accessor method
	
	public static function getFacadeAccessor() {
		
		return 'Urlshortner';
	
	}

}
