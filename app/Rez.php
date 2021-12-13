<?php

namespace App;

use harmonic\InertiaTable\InertiaModel;

class Rez extends InertiaModel {
	
	/**
	 * Pagination items per page to display in table
	 *
	 * @var integer
	 */
        protected $table = 'users';
	protected $perPage = 10;
	
}