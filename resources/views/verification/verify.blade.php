@extends('layouts.base')

@section('title', 'Verify email')

@section('content')
    <a href="/verify/{{ $id }}/{{ $hash }}">Verify email</a>
@endsection
