<?php


namespace Xmaestro\Urlshortner;

use \Artisan;

class Urlshortner {
	public $shortenedUrl;

	// Constructor
	function __construct($shortenedUrl = '') {
		$this->checkTableNotFound ();

		$this->setURL ( $shortenedUrl );
	}
	public function getURL() {
		return $this->shortenedUrl;
	}
	public function setURL($shortenedUrl) {
		$this->shortenedUrl = $shortenedUrl;
	}
	public function generateRandomKey() {
		$randomString = str_random ( 4 );

		$record = \DB::table ( 'urlshortner' )->where ( 'short_url', trim ( $randomString ) )->first ();

		while ( $record !== null ) {

			$this->generateRandomKey ();
		}

		return $randomString;
	}
	public function saveRoute() {
		$randomString = $this->generateRandomKey ();

		\DB::table ( 'urlshortner' )->insert ( array (

				'short_url' => $randomString,
				'actual_url' => $this->shortenedUrl,
				'expired' => 0,
				'created_at' => date ( 'Y-m-d H:i:s', time () )
		) )

		;

		return $randomString;
	}
	public function reRoute() {
		\Route::any ( 'r{id}', function ($id) {

			$record = \DB::table ( 'urlshortner' )->where ( 'short_url', trim ( $id ) )->first ();

			if ($record === null) {

				echo 'URL not found!';

				exit ();
			} else {

				$timeDiff = time () - strtotime ( $record->created_at );

				if ($timeDiff < 3600 && $record->expired == 0) {

					return \Redirect::away ( $record->actual_url );
				} else {

					\DB::table ( 'urlshortner' )->where ( 'short_url', $record->short_url )->update ( array (

							'expired' => 1
					) )

					;

					echo 'URL expired! or does not exist!';
					exit ();
				}
			}
		} )->where ( 'id', '[A-Za-z0-9]+' );
	}
	public function checkTableNotFound() {
		\Event::listen ( 'table.notfound', function () {

			if (! \Schema::hasTable ( 'urlshortner' )) {

				\Artisan::call ( 'migrate', array (
						'--bench' => 'xmaestro/urlshortner'
				) );
			}
		} );
	}
	public function make() {
		$rules = array (

				'url' => 'required'
		);

		$validator = \Validator::make ( \Input::all (), $rules );

		\Event::fire ( 'table.notfound' );

		if (! \Input::get ( '_token' )) {

			return \View::make ( 'urlshortner::form', array (
					'messages' => ''
			) );
		} else {

			if ($validator->passes ()) {

				$this->shortenedUrl = trim ( \Input::get ( 'url' ) );

				$key = $this->saveRoute ();

				\View::share ( 'success', true );

				\View::share ( 'message', 'Your url has been shortened to : ' . url () . '/r' . $key );
			} else {

				\View::share ( 'success', false );

				\View::share ( 'message', 'Validation failed! Please try again.' );
			}

			return \View::make ( 'urlshortner::form', array (
					'messages' => $validator->messages ()
			) );
		}
	}
}