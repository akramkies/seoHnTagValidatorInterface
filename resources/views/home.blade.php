@extends('layouts.default')

@section('main')
<div class="container mt-5">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-10">
            <h1 class="h3 mb-2 text-gray-800">Results</h1>
        </div>

        <div class="col d-grid d-md-flex justify-content-md-end">
            <a href="{{ route('validator.create') }}" class="btn btn-dark">New crawl</a>
        </div>
    </div>
    <hr/>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Url</th>
                <th>State</th>
                <th>number of crawled pages</th>
                <th>Crawl date</th>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            @foreach($res as $value)
                <tr>
                    <td class="fw-bold"> {{$value->id}} </td>

                    <td>
                        <a href="{{ route('validator.show', $value->id) }}">
                            {{$value->url}}
                        </a>
                    </td>

                    <td class="<?= $value->state === 'complete' ? 'text-success' : 'text-warning' ?>">
                        {{$value->state}}
                    </td>

                    <td> {{$value->nb_crawled_page}} </td>

                    <td> {{$value->created_at->format('d M. Y')}} </td>

                    <td>
                        <form method="POST" action="{{ route('websites.destroy', $value->id) }}">
                            @method('delete')
                            @csrf
                            <input type="submit" class="btn btn-link text-danger" value="delete" />
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection