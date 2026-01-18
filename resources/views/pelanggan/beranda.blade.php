@extends('layouts.pelanggan')

@section('title', 'Booking Lapangan Futsal Online')

@section('content')
    @include('partials.landing.hero')
    @include('partials.landing.features')
    @include('partials.landing.fields')
    @include('partials.landing.steps')
    @include('partials.landing.gallery')
    @include('partials.landing.testimonials')
    @include('partials.landing.blog')
    @include('partials.landing.cta')
@endsection
