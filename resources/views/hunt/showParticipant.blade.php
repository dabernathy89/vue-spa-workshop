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

                    @if (!$hunt->includesUser(auth()->user()) && $hunt->isOpen)
                        <form class="mt-3" action="{{ route('hunt.add_user', ['hunt' => $hunt->id, 'user' => auth()->id()]) }}" method="POST">
                            @csrf
                            <button title="Join Scavenger Hunt" class="btn btn-secondary" type="submit">Join <i class="fas fa-user-plus"></i></button>
                        </form>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="card-body">
                        @include('partials.errors')
                    </div>
                @endif

                <div class="card-body">
                    <h3>Goals</h3>

                    <ul class="list-group">
                        @if ($hunt->includesUser(auth()->user()))
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
            </div>
        </div>
    </div>
</div>
@endsection
