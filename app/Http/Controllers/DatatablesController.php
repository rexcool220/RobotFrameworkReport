<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\suite;

use App\test;

use App\kw;

use App\kwDetail;

use Yajra\Datatables\Datatables;

class DatatablesController extends Controller
{
    public function getSuite()
    {
        return view('datatables.suite');
    }
    public function suiteData()
    {
        $suite = suite::select(['suiteId', 'created_at', 'updated_at', 'source', 'id', 'name', 'status', 'endTime', 'startTime', 'criticalTestsFail', 'criticalTestsPass', 'allTestsFail', 'allTestsPass', 'error']);
        return Datatables::of($suite)
            ->addIndexColumn()
            ->addColumn('detail', function ($suite) {
                return '<a href="/report/'.$suite->suiteId.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-signal"></i> Detail</a>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }
}
