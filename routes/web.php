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
#	For first iteration we'll keep them as normal pages first without AJAX,
#	As to do it properly we need to do client side routing for browser history
#	Will handle later, if its too difficult we'll drop the AJAX bit

#	Home/Main page default view all: Order by top(favourites)
Route::get('/', 'HomeController@index') -> name('home.default');

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
Route::get('/{sort}/{page?}', 'HomeController@index') -> name('home.sorted');

#	View user favourites
Route::get('/favourites/{sort}/{page?}', 'TestController@test') -> name('favourites');

#	View according to user defined list of tags
Route::get('/list/{listname}/{sort}/{page?}', 'TestController@test') -> name('userlist');

#	View according to tags
Route::get('/tag/{tag}/{sort}/{page?}', 'TestController@test') -> name('tag');

#	View according to search
Route::get('/search/{query}/{page?}', 'TestController@test') -> name('search');

#	Post direct link: include post title in url to make it user/SEO friendly. 
Route::get('/post/{postId}/{postTitle}', 'TestController@test') -> name('post');

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
Route::Post('/createpost', 'TestController@test');

# 	update post
Route::Post('/updatepost', 'TestController@test');

#	delete post
Route::Post('/deletepost', 'TestController@test');

#	create a new comment
Route::Post('/createcomment', 'TestController@test');

#	update comment
Route::Post('/updatecomment', 'TestController@test');

#	delete comment
Route::Post('/deletecomment', 'TestController@test');

#	report post
Route::Post('/reportpost', 'TestController@test');

#	send message
Route::Post('/sendmessage', 'TestController@test');

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
Route::get('/rest/home/{sort}/{datetime}/{page}', 'HomeController@restHome');

#	view according to lists ajax endpoint
Route::get('/rest/list/{listName}/{sort}/{datetime}/{page?}', 'TestController@test');

#	view according to tags ajax endpoint
Route::get('/rest/tags/{tag}/{sort}/{datetime}/{page?}', 'TestController@test');

#	View according to search ajax endpoint
Route::get('/rest/search/{query}/{datetime}/{page?}', 'TestController@test');

#	Post content ajax endpoint
Route::get('/rest/post/{postId}/{postTitle}', 'TestController@test');

####################################################################################

### RESTful Form POST endpoints

####################################################################################

# 	create a new post
Route::Post('/rest/createpost', 'TestController@test');

# 	update post
Route::Post('/rest/updatepost', 'TestController@test');

#	delete post
Route::Post('/rest/deletepost', 'TestController@test');

#	create a new comment
Route::Post('/rest/createcomment', 'TestController@test');

#	update comment
Route::Post('/rest/updatecomment', 'TestController@test');

#	delete comment
Route::Post('/rest/deletecomment', 'TestController@test');

#	Favourite post
Route::Post('/rest/favourite', 'TestController@test');

#	Add/+1 tag to post
Route::Post('/rest/addtag', 'TestController@test');

#	Report Post
Route::Post('/rest/reportpost', 'TestController@test');

####################################################################################

### Admin routes

####################################################################################

#	Admin page
Route::Post('/admin', 'TestController@test') -> name('admin');

# 	add admin form post routes here etc

####################################################################################

### Misc

####################################################################################

