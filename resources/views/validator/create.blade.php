@extends('layouts.default')

@section('main')
    <div class="main">
        <div class="container-sm">
            <div class="row mb-3">
                <div class="col-md-7 offset-md-2">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Hn validator</legend>

                        <form method="POST" action="{{ route('validator.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="urlInput" class="form-label">Url*</label>
                                <input type="url" class="form-control" id="urlInput" name="url" placeholder="https://www.example.com">
                            </div>
                            @error('url')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="mb-3">
                                <label for="concurrent" class="form-label">Concurrent requests</label>
                                <input type="number" class="form-control" id="concurrent" name="concurrent" value="3" >
                            </div>
                            @error('concurrent')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="mb-3 form-check">
                                <input name="agree" type="checkbox" class="form-check-input" id="onlyErrors">
                                <label class="form-check-label" for="onlyErrors">Display only errors</label>
                            </div>
                            <button type="submit" class="btn btn-dark">Crawl</button>
                            <a href="{{ url('/') }}" class="btn btn-primary">Archive</a>
                        </form>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
@endsection