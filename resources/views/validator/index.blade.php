@extends('layouts.default')

@section('main')
<div class="container mt-5">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Results</h1>
    <hr/>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Url</th>
                <th>Is valid</th>
                <th>Errors</th>
                <th>Tags</th>
            </tr>
        </thead>

        <tbody>
            @foreach($res as $value)
                <tr>
                    <td>
                        <a href="{{$value['url']}}" target="_blank">
                            {{$value['url']}}
                        </a>
                    </td>
                    <td> {{$value['is_valid']}} </td>
                    <td> {{$value['errors']}} </td>
                    <td>
                        <ul style="list-style-type: none;" >
                            @foreach($value['tags'] as $tag)
                                <li> {{$tag['tag']}} </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th>Url</th>
                <th>Is valid</th>
                <th>Errors</th>
                <th>Tags</th>
            </tr>
        </tfoot>
    </table>

</div>
@endsection