@extends('layouts.app')

@section('page', 'home')


@section('title', 'Beranda | Futsal ACR')

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const page = document.body.dataset.page;

        // ❗ Scroll spy hanya untuk beranda
        if (page !== 'home') return;

        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link[href^="#"]');

        window.addEventListener('scroll', () => {
            let current = 'hero';

            sections.forEach(section => {
                const sectionTop = section.offsetTop - 120;
                if (window.pageYOffset >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    });
</script>
@endpush