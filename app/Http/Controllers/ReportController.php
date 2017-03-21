<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;

class ReportController extends Controller
{
    public function report(Request $request) {
    	$report = new Report;
	$report->reported_id = $request->input('id');
	$report->report_type = $request->input('type');
	$report->report_comment = $request->input('report_comment');
	$report->save();
    }
}
