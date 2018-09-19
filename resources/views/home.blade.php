@extends('layouts.app')

@section('content')
    <router-view @success="success"></router-view>
@endsection
