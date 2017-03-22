<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\User;
use App\Post;
use App\Comment;
use App\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //report inappropriate content for deletion
    public function report(Request $request) {
	if (self::validate_report($request)) {
    	    $report = new Report;
	    $report->reported_id = $request->input('reported_id');
	    $report->report_type = $request->input('report_type');
	    $report->report_comment = $request->input('report_comment');
	    $report->status = true;
	    $report->user_id = Auth::id();
	    $report->save();
	    return response()->json(['status' => 'success', 
		'reason' => 'valid']); 
	}
	else {
	    return response()->json(['status' => 'failure',
		'reason' => 'invalid reported_id or report_type']);
	}
    }
    //report inaccurate tagging for removal
    public function remove_tag(Request $request) {
	if (self::validate_remove_tag($request)) {
    	    $report = new Report;
	    $report->tag_id = $request->input('tag_id');
	    $report->post_id = $request->input('post_id');
	    $report->report_comment = $request->input('report_comment');
	    $report->status = true;
	    $report->user_id = Auth::id();
	    $report->save();
	    return response()->json(['status' => 'success', 
		'reason' => 'valid']); 
	}
	else {
	    return response()->json(['status' => 'failure',
		'reason' => 'invalid tag_id or post_id']);
	}
    }

    public function validate_report(Request $request) {
	if ($request->input('report_type') == 'user') {
	    try {
	    	User::findOrFail($request->input('reported_id'));
	    } catch (ModelNotFoundException $e) {
		return false;
	    }
	}
	else if ($request->input('report_type') == 'post') {
	    try {
	    	Post::findOrFail($request->input('reported_id'));
	    } catch (ModelNotFoundException $e) {
		return false;
	    }
	}
	else if ($request->input('report_type') == 'comment') {
	    try {
	    	Comment::findOrFail($request->input('reported_id'));
	    } catch (ModelNotFoundException $e) {
		return false;
	    }
	}
	else if ($request->input('report_type') == 'tag') {
	    try {
	    	Tag::findOrFail($request->input('reported_id'));
	    } catch (ModelNotFoundException $e) {
		return false;
	    }
	}
	else {
	    //invalid report_type
	    return false;
	}
	return true;
    }

    public function validate_remove_tag(Request $request) {
	try {
	    Tag::findOrFail($request->input('tag_id'));
	} catch (ModelNotFoundException $e) {
	    return false;
	}
	try {
	    Post::findOrFail($request->input('post_id'));
	} catch (ModelNotFoundException $e) {
	    return false;
	}
	return true;
    }
}
