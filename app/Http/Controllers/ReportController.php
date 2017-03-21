<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;

class ReportController extends Controller
{
    //report inappropriat content for deletion
    public function report(Request $request) {
    	$report = new Report;
	$report->reported_id = $request->input('reported_id');
	$report->report_type = $request->input('report_type');
	$report->report_comment = $request->input('report_comment');
	$report->status = true;
	$report->save();
    }
    //report inaccurate tagging for removal
    public function remove_tag(Request $request) {
    	$report = new Report;
	$report->tag_id = $request->input('tag_id');
	$report->post_id = $request->input('post_id');
	$report->report_comment = $request->input('report_comment');
	$report->status = true;
	$report->save();
    }

    //TODO validation
}
