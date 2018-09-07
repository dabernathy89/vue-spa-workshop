@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">All Scavenger Hunts</div>
                <ul class="list-group list-group-flush">
                    @foreach($hunts as $hunt)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <a href="{{ route('hunt.show', ['hunt' => $hunt->id]) }}">
                                    {{ $hunt->name }}
                                </a>
                                @if ($hunt->isClosed)
                                    - <em>closed</em>
                                @endif
                            </span>

                            @if (!$hunt->includesUser(auth()->user()) && $hunt->isOpen)
                                @include('hunt.partials.join-hunt-button')
                            @elseif ($hunt->isOpen)
                                @include('hunt.partials.leave-hunt-button')
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
