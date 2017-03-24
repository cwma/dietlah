<?php

####################################################################################

### Standard Web Views

####################################################################################

############################

#   For all views in this section, aside from main page, the rest will have an equivalent
#	REST endpoint for ajax call. When user clicks links we'll use AJAX to load the new data
#	into the main index page. The main reason the routes for Lists, Tags and Search also
# 	have a regular end point is to allow users to save urls or link certain pages directly.
#
#	For first iteration we'll have ajax infinite scrolling, but changing views will just
#	be regular page changes as there needs to be substantial client side routing code
#	to handle browser history properly (backbone.js router)
#	If this is too complicated we can drop it.
#	
#	For forms we can do regular post first. Once everything works we'll add AJAX.

#	Home/Main page default view all: Order by top(favourites)
Route::get('/', 'HomePageController@index') -> name('home.default');

#	Sort Types:
#		New, Popular(favourites), Comments, Relevance(tag/list only)
#	Relevance: we can determine relevance of a post by how many times the relevant tags were voted
#
#	Thinking about it maybe we should have Likes as well in addition to favourites
#	Just because you like a post doesnt neccessarily mean you want to bookmark it
#	Like in youtube, just because you like a video doesnt mean you want to keep
#	it in your favourites
#
#	Home/Main page view all: order by sort type
Route::get('/view/{sort}/{page?}', 'HomePageController@homeAll') -> name('home.sorted');

#	View according to search
Route::get('/search/{query}/{page?}', 'TestController@test') -> name('search');

#	Post direct link: include post title in url to make it user/SEO friendly. 
//Route::get('/post/{postId}/{postTitle}', 'TestController@test') -> name('post');
Route::get('/post/{postId}', 'PostController@post') -> name('post');//without title for now, easier to nagivate 

Route::get('/createpost', 'PostController@newpost') -> name('post');

#	update an existing post
Route::get('/update/{postid}', 'TestController@test') -> name('edit');

############################

#	Login
Route::get('/login', 'TestController@test') -> name('login');

#	Register
Route::get('/register', 'TestController@test') -> name('register');

#	view and send messages
Route::get('/messages', 'TestController@test') -> name('messages');

#	For user to manage lists
Route::get('/manage', 'TestController@test') -> name('manage');

#	Show all tags and some info: num of posts etc
Route::get('/viewtags', 'TestController@test') -> name('viewtags');

#	User profile page
Route::get('/profile/{username}', 'TestController@test') -> name('profile');

####################################################################################

### Form POST endpoints

####################################################################################

# 	create a new post
Route::post('/createpost', 'PostController@createPost');

# 	update post
Route::post('/updatepost', 'PostController@updatePost');

#	delete post
Route::post('/deletepost', 'PostController@deletePost');

#	create a new comment
Route::post('/createcomment', 'CommentController@createComment');

#	update comment
Route::post('/comment', 'CommentController@comment');
Route::post('/updatecomment', 'CommentController@updateComment');

#	delete comment
Route::post('/deletecomment', 'CommentController@deleteComment');

#	report post
Route::post('/reportpost', 'TestController@test');

#	send message
Route::post('/sendmessage', 'TestController@test');

####################################################################################

### RESTful view end points

####################################################################################

#	Infinite Scroll Pagination
#	You may have noticed that all the URIs take in a datetime parameter
#	This is the time at which the first page was loaded
#	we do this to select the next set of items and exclude items that have been
#	created after that particular datetime. This prevents duplicates for showing up 
#
#	Example:
#	User A Browses page 1. User B creates new item. User A scrolls to page 2
#	New item "bumps" the order of posts down and when new page is retrieved
#	there ends up being a duplicate
#
#	see: https://coderwall.com/p/lkcaag/pagination-you-re-probably-doing-it-wrong
#
#	Above solution only applies to stable rows, ie. sort by date
#	We'll have to find a more.. exotic solution for sort by favourite count
#	Duplicates can be prevented by checking id's and adding only unique ones
#	However some entries can "disappear" if they're bumped into page 1 when you're
#	loading into page 2.

#	Think we could do "fake" pagination, where data for 120 rows is preloaded in js
#	and "paginate" every 12 rows client side, and call server every 120 rows
#	This mitigates the issue a bit.
#
#	Alternatively load all IDs and lazy load paginate as user scrolls using those IDs

#	main home page ajax endpoint
Route::get('/rest/postfeed/{order}/{range}/{datetime}/{page}', 'HomePageController@restPostFeed');

#	View according to search ajax endpoint
Route::get('/rest/search/{query}/{datetime}/{page?}', 'TestController@test');

#	Post content ajax endpoint
Route::get('/rest/post/{postId}/', 'HomePageController@restPost');

#	post comment content ajax endpoint
Route::get('/rest/comments/{postId}/{datetime}/{page}', 'HomePageController@restComments');



####################################################################################

### RESTful Form POST endpoints

####################################################################################

# 	create a new post
Route::post('/rest/createpost', 'PostController@createPost');

# 	update post
Route::post('/rest/updatepost', 'TestController@test');

#	delete post
Route::post('/rest/deletepost', 'TestController@test');

#	create a new comment
Route::post('/rest/createcomment', 'TestController@testCreateComment');

#	update comment
Route::post('/rest/updatecomment', 'TestController@test');

#	delete comment
Route::post('/rest/deletecomment', 'TestController@test');

#	Favourite post
Route::post('/rest/favourite', 'TestController@testFavourite');

#	Like post
Route::post('/rest/like', 'TestController@testLike');

#	Add/+1 tag to post
Route::post('/rest/addtag', 'TestController@test');

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
Route::get('/admin', 'TestController@test') -> name('admin');

# 	add admin form post routes here etc

####################################################################################

### Misc

####################################################################################


Auth::routes();

