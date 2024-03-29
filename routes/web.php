<?php

####################################################################################

### Standard Web Views

####################################################################################

Route::get('/', 'HomePageController@index') -> name('home.default');

#	Home/Main page view all: order by sort type
Route::get('/view/{sort}/{range}', 'HomePageController@indexFiltered') -> name('home.sorted');

#	View according to search
Route::get('/search', 'HomePageController@indexSearch') -> name('home.search');

#	Post direct link: include post title in url to make it user/SEO friendly.
//Route::get('/post/{postId}/{postTitle}', 'TestController@test') -> name('post');
Route::get('/post/{postId}', 'PostController@post') -> name('post'); //without title for now, easier to nagivate

Route::get('/createpost', 'PostController@newpost') -> name('post.create');

#	update an existing post
Route::get('/update/{postid}', 'PostController@editpost') -> name('post.edit');

############################

#	view and send messages
Route::get('message/{id?}', 'MessageController@chatHistory')->name('message.read');

Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
   Route::post('message/send', 'MessageController@ajaxSendMessage')->name('message.new');
   Route::delete('message/delete/{id}', 'MessageController@ajaxDeleteMessage')->name('message.delete');
});

Route::get('/rest/userlist.json', 'MessageController@users');
Route::post('/rest/getuserid', 'MessageController@getuserid');

#	User profile page
Route::get('/profile/{userid}', 'ProfileController@viewProfile') -> name('profile.view');

Route::get('/myprofile', 'ProfileController@myProfile') -> name('profile.my');

Route::get('/editprofile', 'ProfileController@editProfile') -> name('profile.edit');

Route::post('/rest/updateprofile', 'ProfileController@updateProfile'); 

Route::get('/about', function () {
	return view('about');
}) ->name('about');

####################################################################################

### Form POST endpoints

####################################################################################

# 	create a new post
Route::post('/createpost', 'PostController@createPost');

# 	update post
Route::post('/updatepost', 'PostController@updatePost');

#	delete post
Route::post('/deletepost', 'PostController@deletePost');

####################################################################################

### RESTful view end points

####################################################################################

#	main home page ajax endpoint
Route::get('/rest/postfeed/{order}/{range}', 'HomePageController@restPostFeed');

#	View according to search ajax endpoint
Route::get('/rest/search', 'HomePageController@restSearch');

#	View according to search ajax endpoint
Route::get('/rest/profile/{userid}', 'ProfileController@restProfile');

#	Post content ajax endpoint
Route::get('/rest/post/{postId}/', 'HomePageController@restPost');

#	post comment content ajax endpoint
Route::get('/rest/comments/{postId}', 'CommentController@restComments');


####################################################################################

### RESTful Form POST endpoints

####################################################################################

# 	create a new post
Route::post('/rest/createpost', 'PostController@createPost');

# 	update post
Route::post('/rest/updatepost', 'PostController@updatePost');

#	delete post
Route::post('/rest/deletepost', 'PostController@deletePost');

#	create a new comment
Route::post('/rest/createcomment', 'CommentController@createComment');

#	update comment
Route::post('/rest/updatecomment', 'CommentController@updateComment');

#	delete comment
Route::post('/rest/deletecomment', 'CommentController@deleteComment');

#	Favourite post
Route::post('/rest/favourite', 'PostController@favouritePost');

#	Like post
Route::post('/rest/like', 'PostController@likePost');

#	Add/+1 tag to post
Route::post('/rest/addtag', 'PostController@updatePostTags');

Route::group(['middleware' => 'auth'], function () {
	#	Report content
	Route::post('/rest/report', 'ReportController@report');

	#	Report inaccurate tagging for removal
	Route::post('/rest/remove_tag', 'ReportController@remove_tag');
});

####################################################################################

### Admin routes

####################################################################################

#	Admin page
Route::get('/admin', 'AdminController@index') -> name('admin');

# 	add admin form post routes here etc

####################################################################################

### Misc

####################################################################################

# email verification routes

Route::get('/email-verification/error', function() {
	return redirect('/email-verification/verify');
});
Route::get('/email-verification/verify', 'Auth\RegisterController@resendVerifyPage') -> name("verify.resendpage");
Route::post('/email-verification/send-email', 'Auth\RegisterController@resendVerify') -> name("verify.resend");

Route::get("/banned", "Auth\RegisterController@banned") -> name("banned");

Auth::routes();
