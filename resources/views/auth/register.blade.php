@extends('layouts.app')
@section('title', 'Register')

@section('content')
<h1 class="visually-hidden">Register</h1>
<div class="row">
    <div class="col-12 col-sm-6 col-lg-3 order-2 order-lg-1">
        <x-cards.interview />
    </div>
    <div class="col-12 col-lg-6 order-1 order-lg-2">
        @include('auth.card_register')

    </div>
    <div class="col col-sm-6 col-lg-3 order-3">
        <x-cards.reviews />
    </div>
</div>

@endsection
