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

                    <div class="mt-3">
                        @include('hunt.partials.close-reopen-hunt-button')

                        @include('hunt.partials.delete-hunt-button')

                        <a href="{{ route('hunt.show.solutions', ['hunt' => $hunt->id]) }}" class="btn btn-primary">View Submitted Solutions</a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="card-body">
                        @include('partials.errors')
                    </div>
                @endif

                <div class="card-body">
                    <h3>Goals</h3>

                    @if ($hunt->isOpen)
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
                        @include('hunt.partials.owner-goals-list')
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
