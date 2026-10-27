@extends('layouts.app')

@section('title', "Lima's Studio Barber — Seu estilo começa aqui")
@section('description', "Barbearia tradicional em Cascavel-CE com o Enos Lima. Corte de cabelo, barba, combo e corte infantil. Agende seu horário online e pague no dia ou pelo site.")

@section('content')
    @include('site.partials.navbar')
    @include('site.partials.hero')
    @include('site.partials.servicos')
    @include('site.partials.sobre')
    @include('site.partials.produtos')
    @include('site.partials.galeria')
    @include('site.partials.blog')
    @include('site.partials.cta')
    @include('site.partials.footer')
@endsection
