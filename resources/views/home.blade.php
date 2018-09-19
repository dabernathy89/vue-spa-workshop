@extends('layouts.app')

@section('content')
    <router-view @success="success"></router-view>
@endsection

@section('js')
<script>
    window.currentUserId = {{ auth()->id() ?? 'null' }};
    window.ownedHunts = @json($owned_hunts);
    window.otherHunts = @json($other_hunts);
</script>
@endsection