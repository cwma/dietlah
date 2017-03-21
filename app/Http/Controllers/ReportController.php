<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\User;
use App\Post;
use App\Comment;
use App\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReportController extends Controller
{
    //report inappropriat content for deletion
    public function report(Request $request) {
	if (validate_report($request)) {
    	    $report = new Report;
	    $report->reported_id = $request->input('reported_id');
	    $report->report_type = $request->input('report_type');
	    $report->report_comment = $request->input('report_comment');
	    $report->status = true;
	    $report->save();
	    return response()->json(['status' => 'success', 
		'reason' => 'valid id']); 
	}
	else {
	    return response()->json(['status' => 'failure',
		'reason' => 'invalid id']);
	}
    }
    //report inaccurate tagging for removal
    public function remove_tag(Request $request) {
	if (validate_remove_tag($request)) {
    	    $report = new Report;
	    $report->tag_id = $request->input('tag_id');
	    $report->post_id = $request->input('post_id');
	    $report->report_comment = $request->input('report_comment');
	    $report->status = true;
	    $report->save();
	    return response()->json(['status' => 'success', 
		'reason' => 'valid id']); 
	}
	else {
	    return response()->json(['status' => 'failure',
		'reason' => 'invalid id']);
	}
    }

    public function validate_report(Request $request) {
	if ($request->input('report_type') == 'user') {
	    try {
	    	App\User::findOrFail($request->input('reported_id'));
	    } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e)
		return false;
	    }
	}
	else if ($request->input('report_type') == 'post') {
	    try {
	    	App\Post::findOrFail($request->input('reported_id'));
	    } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e)
		return false;
	    }
	}
	else if ($request->input('report_type') == 'comment') {
	    try {
	    	App\Comment::findOrFail($request->input('reported_id'));
	    } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e)
		return false;
	    }
	}
	else if ($request->input('report_type') == 'tag') {
	    try {
	    	App\Tag::findOrFail($request->input('reported_id'));
	    } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e)
		return false;
	    }
	}
	else {
	    return false;
	}
	return true;
    }

    public function validate_remove_tag(Request $request) {
	try {
	    App\Tag::findOrFail($request->input('tag_id'));
	} catch (Illuminate\Database\Eloquent\ModelNotFoundException $e)
	    return false;
	}
	try {
	    App\Post::findOrFail($request->input('post_id'));
	} catch (Illuminate\Database\Eloquent\ModelNotFoundException $e)
	    return false;
	}
	return true;
    }
}
