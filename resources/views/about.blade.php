@extends('template')

@section('title', 'About DietLah!')

@section('meta')
    <meta property="og:url"           content="{{url('/')}}" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="DietLah! - About" />
    <meta property="og:description"   content="A community portal for sharing diet tips in Singapore" />
    <meta property="og:image"         content="{{url('/')}}/logo.png" />
@stop

@section('page-content')
<div id="fb-root"></div>
<div class="container">
    <br><br>
    <h1 class="header center light-green-text"><img src="logo.png"></h1>
    <h1 class="header center light-green-text">DietLah!</h1>
    <div class="row center">
        <h5 class="header col s12 light">A community portal for sharing diet tips in Singapore</h5>
    </div>

    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-green-text"><i class="material-icons">tag_faces</i></h2>
            <h5 class="center">Who are we</h5>

            <p class="light">We're a group of students from NUS who built this site as part of a module that introduces students to building web applications.
                we're still new to many things so bear with us as we learn and build a better site with your suggestions and feedback!</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-green-text"><i class="material-icons">lightbulb_outline</i></h2>
            <h5 class="center">Why Diet?</h5>

            <p class="light">Dieting can be challenging in Singapore, especially on a diet that restricts intake of certain foods. 
                This is especially so for low carb diets such as Keto and Atkins, as Carbohydrates play a central role in many local cuisine. 
                We hope to make this easier with a community driven portal for crowd sourcing information on dining locations, recipes, and in general tips and tricks for dieting in Singapore.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-green-text"><i class="material-icons">mail_outline</i></h2>
            <h5 class="center"><a href="https://docs.google.com/forms/d/e/1FAIpQLScPkJL7MgFZJVzwli_D0fqGhar65LhotL2mLfAbLdbt7oL5dQ/viewform?usp=sf_link" target="_blank">Contact Us</a></h5>

            <p class="light">We'd love to hear from you on how we can improve the user experience here at DietLah! Feel free to drop us a message on your comments, suggestions and feedback. 
            </p>
          </div>
        </div>
      </div>

    </div>
    <div class="row"><br>
    <div><a href="https://twitter.com/share" class="twitter-share-button" data-url="{{url('/')}}/about" data-show-count="false">Tweet</a>
        <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
    <a href="https://twitter.com/DietLahSG" class="twitter-follow-button" data-show-count="false">Follow @DietLahSG</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script></div>
    <div class="fb-like" data-width="350" data-href="{{url('/')}}/about" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
    </div>
</div>
@stop

@section("footer-addition")
<div class="container">
    <div class="row">
        <div class="col l6 s12">
            <h5 class="white-text">An NUS Capstone Project</h5>
            <p class="grey-text text-lighten-4">By Team 1-A of CS3226 AY2016/2017</p>
        </div>
        <div class="col l4 offset-l2 s12">
            <h5 class="white-text">Links</h5>
            <ul>
                <li><a class="grey-text text-lighten-3" href="http://www.nus.edu.sg/">National University of Singapore</a></li>
                <li><a class="grey-text text-lighten-3" href="http://isteps.comp.nus.edu.sg/event/10th-steps">10th Steps</a></li>
                <li><a class="grey-text text-lighten-3" href="http://www.comp.nus.edu.sg/~stevenha/cs3226.html">CS3226</a></li>
            </ul>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    $(window).bind("load", function() {
       $.getScript('js/social.js', function() {});
    });
</script>
@stop