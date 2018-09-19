@extends('layouts.app')

@section('content')
<show-participant @success="success"></show-participant>
@endsection

@section('js')
<script>
    window.currentHunt = @json($hunt);
    window.currentUserId = {{ auth()->id() ?? 'null' }};
</script>
@endsection