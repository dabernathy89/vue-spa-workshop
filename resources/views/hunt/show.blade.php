@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>
                        {{ $hunt->name }}
                        @if ($hunt->isClosed)
                            <em>- closed</em>
                        @endif
                    </h1>
                    <small>Owned by: <em>{{ $hunt->owner->name }}</em></small>

                    @if ($hunt->winner_id)
                        <div class="alert alert-success mt-3"><h4 class="mb-0">Winner: {{ $hunt->winner->name }}</h4></div>
                    @endif

                    @if (!$hunt->ownedBy(auth()->user()) && !$hunt->includesUser(auth()->user()) && $hunt->isOpen)
                        <form class="mt-3" action="{{ route('hunt.add_user', ['hunt' => $hunt->id, 'user' => auth()->id()]) }}" method="POST">
                            @csrf
                            <button title="Join Scavenger Hunt" class="btn btn-secondary" type="submit">Join <i class="fas fa-user-plus"></i></button>
                        </form>
                    @endif

                    @if ($hunt->ownedBy(auth()->user()))
                        <div class="mt-3">
                            @if ($hunt->isOpen)
                                @include('hunt.partials.close-hunt-button')
                            @endif

                            @include('hunt.partials.delete-hunt-button')
                        </div>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="card-body">
                        @include('partials.errors')
                    </div>
                @endif

                <div class="card-body">
                    <h3>Goals</h3>

                    @if ($hunt->isOpen && $hunt->ownedBy(auth()->user()))
                        <form class="mb-3 mt-3" action="{{ route('hunt.goal.store', ['hunt' => $hunt->id]) }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-12 col-sm-8">
                                    <input class="form-control" type="text" name="title" placeholder="Insert Goal Title...">
                                </div>

                                <div class="col-12 col-sm-4">
                                    <input class="form-control btn btn-primary" type="submit" value="Create A Goal">
                                </div>
                            </div>
                        </form>
                    @endif

                    <ul class="list-group">
                        @if ($hunt->ownedBy(auth()->user()))
                            @include('hunt.partials.owner-goals-list')
                        @elseif ($hunt->includesUser(auth()->user()))
                            @include('hunt.partials.participant-goals-list')
                        @else
                            @forelse ($hunt->goals as $goal)
                                <li class="list-group-item">
                                    {{ $goal->title }}
                                </li>
                            @empty
                                <li class="list-group-item"><em>This Scavenger Hunt does not have any goals yet.</em></li>
                            @endforelse
                        @endif
                    </ul>
                </div>

                @if ($hunt->ownedBy(auth()->user()))
                    <div class="card-body">
                        <h3>Participants</h3>
                        @forelse ($hunt->participants as $participant)
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center {{ $hunt->winner_id === $participant->id ? 'text-success' : '' }}">
                                    <h4 class="mb-0">{{ $participant->name }}</h4>
                                    @if ($hunt->winner_id === $participant->id)
                                        <h5 class="mb-0"><em>Winner</em></h5>
                                    @endif

                                    @if (!$hunt->winner_id)
                                        @include('hunt.partials.choose-winner-button')
                                    @endif
                                </div>

                                <div class="card-body">
                                    @if ($participant->solutions->where('goal.hunt_id', $hunt->id)->count())
                                        <div class="card-columns mt-3">
                                            @foreach ($participant->solutions->where('goal.hunt_id', $hunt->id) as $solution)
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-0">{{ $solution->goal->title }}</h5>
                                                    </div>
                                                    <img class="card-img-bottom" src="{{ asset($solution->image) }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p>No submitted solutions</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">This Scavenger Hunt does not have any participants yet.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
