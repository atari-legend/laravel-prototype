@extends('layouts.app')
@section('title', 'Atari ST games search results')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3 order-2 order-lg-1">
            <x-cards.latest-comments />
        </div>
        <div class="col-12 col-lg-6 order-1 order-lg-2">
            @include('games.card_search_results')
        </div>
        <div class="col col-sm-6 col-lg-3 order-3">
            <x-cards.screenstar />
        </div>
    </div>
@endsection
