@extends('layouts.default')

@section('main')
<div class="container mt-5">
    <h2>{{ $url }}</h2>

    <h2 class="<?= $statut === 'complete' ? 'text-success' : 'text-warning' ?>">
        {{$statut}}

        @if ($statut === 'in_progress')
            <div class="spinner-border" style="width: 2.5rem; height: 2.5rem;" role="status"></div>
        @endif
    </h2>

    <div class="col d-grid d-md-flex justify-content-md-end">
        <a href="{{ url('/') }}" class="btn btn-dark">Archive</a>
    </div>

    <h2 class="h3 mb-2 text-gray-800">Results</h2>
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
                        <a href="{{ $value->url }}" target="_blank">
                            {{$value->url}}
                        </a>
                    </td>
                    <td> <?= $value->is_valid === 1 ? 'true' : 'false' ?> </td>
                    <td> {{$value->errors}} </td>
                    <td>
                        <ul style="list-style-type: none;" >
                            @foreach($value->tags as $tag)
                                <li> {{ $tag }} </li>
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