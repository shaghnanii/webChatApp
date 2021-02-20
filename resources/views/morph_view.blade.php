@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Morph Header') }}</div>

                    <div class="card-body">
                        <h4 class="text-muted">Posts And Posts Comments</h4>
                        @forelse ($posts as $post)
                            <h5>Post Title: {{ $post->title }}</h5>
                            <p>Description: {{ $post->content }}</p>

                            <hr>
                            <h6>Comments</h6>
                            @forelse ($post->comments as $comment)
                                <p>{{ $loop->index }}. {{ $comment->body }}</p>
                            @empty
                                <div class="alert alert-warning">No Comments</div>


                            @endforelse
                            <hr>
                        @empty
                            <div class="alert alert-warning">No Posts</div>

                        @endforelse


                        <hr>

                        <h4 class="text-muted">Page And Page Comments</h4>
                        @forelse ($pages as $page)
                            <h5>Page Body: {{ $page->body }}</h5>

                            <hr>
                            <h6>Comments</h6>
                            @forelse ($page->comments as $comment)
                                <p>{{ $loop->index }}. {{ $comment->body }}</p>
                            @empty
                                <div class="alert alert-warning">No Comments</div>


                            @endforelse
                            <hr>
                        @empty
                            <div class="alert alert-warning">No Pages</div>

                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
