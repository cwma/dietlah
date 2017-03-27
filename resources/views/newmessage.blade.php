@extends('template')

@section('title', 'DietLah!')

@section('page-content')

<div class="container">

    <div> <!-- use this to style size later -->
        <div class="row">
            <h3>All Users</h3>
        </div>

        <div class="row">
            <!--for each users here-->
                <table class="table">
                    <tr>
                        <td>
                            <!-- user image here--><p>User pic</p>
                            <!-- user name here --><p>User name</p>
                        </td>
                        <td>
                            <!--send message link here--><a href="./newmessage">Send Message</a>
                        </td>
                    </tr>
                </table>
            <!--end for each-->
        </div>
    </div>

</div>
@stop
