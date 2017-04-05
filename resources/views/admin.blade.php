@extends('template')

@section('title', "Report handling")

@section('page-content')
<div class="container">

    <div class="row">
        <h3>Reports</h3>
    </div>

    <div class="row">
        <!--for each report here-->
            <table class="table">
                <tr>
                    <td>
                        <!-- report type here (offending element)--><p>Report type</p>
                    </td>
                    <td>
                        <!-- report source here--><p>Report source</p>
                    </td>
                    <td>
                        <!--report text here--><p>report text</p>
                    </td>
                </tr>
            </table>
        <!--end for each-->
    </div>

</div>
@stop
