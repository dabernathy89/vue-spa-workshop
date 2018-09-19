@extends('layouts.app')

@section('content')
<show-owner @success="success"></show-owner>
@endsection

@section('js')
<script>
    window.currentHunt = @json($hunt);
</script>
@endsection