@extends('layouts.app')
@section('title', "Atari ST software {$software->name}")
@section('robots', 'follow,noindex')

@section('content')
    <h1 class="visually-hidden">Software: {{ $software->name }}</h1>
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3 order-2 order-lg-1">
            <x-cards.reviews />
            <x-cards.trivia />
        </div>
        <div class="col-12 col-lg-6 order-1 order-lg-2">
            @include('menus.card_software')
        </div>
        <div class="col col-sm-6 col-lg-3 order-3">
            <x-cards.latest-comments />
        </div>
    </div>
@endsection

