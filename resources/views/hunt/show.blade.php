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

                @if ($hunt->owner->id === auth()->id())
                    <div class="card-body">
                        @include('partials.errors')
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

                <div class="card-body">
                    <h3>Goals</h3>
                    <ul class="list-group">
                        @forelse ($hunt->goals as $goal)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $goal->title }}
                                @if ($hunt->owner->id === auth()->id())
                                    <form action="{{ route('hunt.goal.delete', ['hunt' => $hunt->id, 'goal' => $goal->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button title="Delete Goal" class="border-0 bg-transparent" type="submit"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item"><em>This Scavenger Hunt does not have any goals yet.</em></li>
                        @endforelse
                    </ul>
                </div>

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
