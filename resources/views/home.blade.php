@extends('layouts.app')

@section('content')
    <home @success="success"></home>
@endsection

@section('js')
<script>
    window.currentUserId = {{ auth()->id() ?? '"null"' }};
    window.ownedHunts = @json($owned_hunts);
    window.otherHunts = @json($other_hunts);
</script>
@endsection