
@extends('layouts.master')

@section('title', 'Movies list')

@section('sidebar')
    @parent
@stop

<style>
    .container_film {
        border-bottom: 1px solid #ccc;
        margin: 50px 0 20px;
        list-style: none;
    }

</style>

@section('content')

    <ul class="listare_filme list">

        @foreach ($movies as $movie)
            <li class="container_film clearfix">
                <div class="row">
                    <div class="poza col-sm-4">
                        <a href="/movie/{{ $movie->id }}" title="{{ $movie->headline }}">
                            <img src="/images/{{ $movie->mainThumb->cached_file ?? 'empty.png' }}" width="250">
                        </a>
                    </div>

                    <div class="descriere col-sm-8">
                        <div class="title">
                            <h2><a href="/movie/{{ $movie->id }}" title="{{ $movie->headline }}">{{ $movie->headline }}</a></h2>
                            <span>({{ $movie->year }})</span><br>
                            <span>{{ $movie->headline }}</span>
                        </div>

                        <ul class="cast">
                            <li>
                                <span>Regia:</span>
                                @foreach ($movie->directors as $director)
                                    <a href="#">{{ $director->name }}</a>
                                @endforeach
                            </li>
                            <li>
                                <span>Cu:</span>
                                @foreach ($movie->actors as $actor)
                                    <a href="#" class="mr-2">{{ $actor->name }}</a>
                                @endforeach
                            </li>
                            <li>
                                <span>Gen film:</span>
                                @foreach ($movie->genres as $genre)
                                    <a href="#" class="mr-2">{{ $genre->name }}</a>
                                @endforeach
                            </li>
                        </ul>

                        <div class="rating">
                            <div class="rating-cinemagia">Rating: {{ $movie->rating }}</div>
                        </div>

                        <div class="short_body bg-light">
                            <br>
                            {{ Str::limit($movie->body, 400) }}
                        </div>


                        <div class="trailer">
                            @if ($movie->trailer)
                                <a href="{{ $movie->trailer->url }}" target="_blank" class="mr-2">
                                    <img src="{{ $movie->trailer->thumbnail_url }}" class="play" style="display:block;" border="0" width="50">
                                </a>
                            @endif
                        </div>



                    </div>
                </div>
            </li>
        @endforeach
    </ul>


@stop

