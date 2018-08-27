@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>{{ $hunt->name }}</h1>
                    <small>Owned by: <em>{{ $hunt->owner->name }}</em></small>
                </div>

                @if ($errors->any())
                    <div class="card-body">
                        @include('partials.errors')
                    </div>
                @endif

                @if ($hunt->owner->id === auth()->id())
                    <div class="card-body">
                        <form action="{{ route('hunt.goal.store', ['hunt' => $hunt->id]) }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-12 col-sm-8">
                                    <label for="name">Goal Title</label>
                                    <input class="form-control" type="text" name="title" placeholder="Insert Goal Title...">
                                </div>

                                <div class="col-12 col-sm-4">
                                    <label>&nbsp;</label>
                                    <input class="form-control btn btn-primary" type="submit" value="Create A Goal">
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                @if($hunt->participants->pluck('id')->contains(auth()->id()) || $hunt->owner->id === auth()->id())
                    <div class="card-body">
                        <h3>Goals</h3>
                        <ul class="list-group">
                            @include('hunt.partials.goals-list')
                        </ul>
                    </div>
                @endif

                <div class="card-body">
                    <h3>Participants</h3>
                    <ul class="list-group">
                        @forelse ($hunt->participants as $participant)
                            <li class="list-group-item">{{ $participant->name }}</li>
                        @empty
                            <li class="list-group-item"><em>This Scavenger Hunt does not have any participants yet.</em></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
