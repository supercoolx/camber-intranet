<?php

namespace App\Http\Controllers;

use harmonic\InertiaTable\Facades\InertiaTable;
use App\Rez;

class RezsController extends Controller {
	public function index() {
        $model = new Rez();
		return InertiaTable::index($model);
	}
}