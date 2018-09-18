@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>
                        Solutions for "{{ $hunt->name }}"
                    </h1>

                    @if ($hunt->winner_id)
                        <div class="alert alert-success mt-3"><h4 class="mb-0">Winner: {{ $hunt->winner->name }}</h4></div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('hunt.show', ['hunt' => $hunt->id]) }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back To Goals</a>
                    </div>
                </div>

                <div class="card-body">
                    <h3>Submitted Solutions</h3>
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
                                                <img class="card-img-bottom" src="{{ $solution->imageSrc }}">
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
            </div>
        </div>
    </div>
</div>
@endsection
