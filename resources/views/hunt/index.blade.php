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
                            <a href="{{ route('hunt.show', ['hunt' => $hunt->id]) }}">
                                {{ $hunt->name }}
                            </a>

                            @if (!auth()->user()->hunts->pluck('id')->contains($hunt->id))
                                <form action="{{ route('hunt.add_user', ['hunt' => $hunt->id, 'user' => auth()->id()]) }}" method="POST">
                                    @csrf
                                    <button title="Join Scavenger Hunt" class="border-0 bg-transparent" type="submit"><i class="fas fa-user-plus"></i></button>
                                </form>
                            @else
                                <form action="{{ route('hunt.remove_user', ['hunt' => $hunt->id, 'user' => auth()->id()]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Leave Scavenger Hunt" class="border-0 bg-transparent" type="submit"><i class="fas fa-sign-out-alt"></i></button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
