<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DateDimensionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 25);

        $dates = DB::table('date_dimension')
            ->orderBy('date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('date_dimension_table', [
            'dates' => $dates,
            'perPage' => $perPage,
        ]);
    }
}

