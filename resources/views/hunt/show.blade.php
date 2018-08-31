@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>
                        {{ $hunt->name }}
                        @if ($hunt->status === 'closed')
                            <em>- closed</em>
                        @endif
                    </h1>
                    <small>Owned by: <em>{{ $hunt->owner->name }}</em></small>

                    @if ($hunt->owner->id === auth()->id())
                        <div class="mt-3">
                            @if ($hunt->status === 'open')
                                <form class="d-inline-block" action="{{ route('hunt.update', ['hunt' => $hunt->id]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="closed">
                                    <button title="Close Scavenger Hunt" class="btn btn-secondary" type="submit">
                                        Close &nbsp;&nbsp;<i class="fas fa-door-closed"></i>
                                    </button>
                                </form>
                            @endif

                            <form class="d-inline-block" action="{{ route('hunt.delete', ['hunt' => $hunt->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button title="Delete Scavenger Hunt" class="btn btn-danger" type="submit">
                                    Delete &nbsp;&nbsp;<i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="card-body">
                        @include('partials.errors')
                    </div>
                @endif

                @if($hunt->participants->pluck('id')->contains(auth()->id()) || $hunt->owner->id === auth()->id())
                    <div class="card-body">
                        <h3>Goals</h3>

                        @if ($hunt->status === 'open' && $hunt->owner->id === auth()->id())
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
                            @include('hunt.partials.goals-list')
                        </ul>
                    </div>
                @endif

                <div class="card-body">
                    <h3>Participants</h3>
                    <ul class="list-group">
                        @forelse ($hunt->participants as $participant)
                            @if ($hunt->winner_id === $participant->id)
                                <li class="list-group-item list-group-item-success">
                                    {{ $participant->name }} <strong>- winner</strong>
                                </li>
                            @elseif ($hunt->owner_id !== auth()->id() || !empty($hunt->winner_id))
                                <li class="list-group-item">{{ $participant->name }}</li>
                            @elseif ($hunt->owner_id === auth()->id())
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $participant->name }}

                                    <form action="{{ route('hunt.update', ['hunt' => $hunt->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="winner_id" value="{{ $participant->id }}">
                                        <button title="Choose Winner" class="border-0 bg-transparent" type="submit"><i class="fas fa-trophy"></i></button>
                                    </form>
                                </li>
                            @endif
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
