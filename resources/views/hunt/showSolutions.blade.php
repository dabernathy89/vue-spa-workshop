@extends('layouts.app')

@section('content')
<show-solutions @success="success"></show-solutions>
@endsection

@section('js')
<script>
    window.currentHunt = @json($hunt);
</script>
@endsection