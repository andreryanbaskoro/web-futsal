@extends('layouts.pelanggan')

@section('title', 'Booking Lapangan Futsal Online')

@section('content')
    @include('partials.pelanggan.hero')
    @include('partials.pelanggan.features')
    @include('partials.pelanggan.fields')
    @include('partials.pelanggan.steps')
    @include('partials.pelanggan.gallery')
    @include('partials.pelanggan.testimonials')
    @include('partials.pelanggan.blog')
    @include('partials.pelanggan.cta')
@endsection
