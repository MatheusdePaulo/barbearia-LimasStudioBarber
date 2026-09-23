@extends('layouts.app')

@section('title', "Lima's Studio Barber — Seu estilo começa aqui")

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
