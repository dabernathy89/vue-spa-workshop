@forelse ($hunt->goals as $goal)
    <li class="list-group-item">
        {{-- Delete Goal button for Owners --}}
        @if ($hunt->owner->id === auth()->id())
            @if ($hunt->status === 'open')
                <div class="d-flex justify-content-between align-items-center">
                    {{ $goal->title }}
                    <form action="{{ route('hunt.goal.delete', ['hunt' => $hunt->id, 'goal' => $goal->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button title="Delete Goal" class="border-0 bg-transparent" type="submit"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            @else
                <p>{{ $goal->title }}</p>
            @endif

            @if ($goal->solutions->count())
            <table class="table table-bordered table-sm mt-2">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Solution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($goal->solutions as $solution)
                        <tr>
                            <td>{{ $solution->user->name }}</td>
                            <td>{{ $solution->title }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            @continue
        @endif

        {{-- If the participant has submitted a solution, display it --}}
        @php
            $user_solution = $goal->solutions->where('user_id', auth()->id())->first();
        @endphp
        @if ($user_solution)
            <p>{{ $goal->title }}</p>
            <div class="alert alert-secondary d-flex justify-content-between align-items-center">
                <span>Your solution: <em>{{ $goal->solutions->where('user_id', auth()->id())->first()->title }}</em></span>
                @if ($hunt->status === 'open')
                    <button class="border-0 bg-transparent" type="button" data-toggle="collapse" data-target="#goal-solution-edit-form{{ $goal->id }}" aria-expanded="false" aria-controls="goal-solution-edit-form{{ $goal->id }}">
                        <i class="fas fa-edit" style="cursor: pointer;"></i>
                    </button>
                @endif
            </div>

            @if ($hunt->status === 'open')
                <div class="collapse mt-3" id="goal-solution-edit-form{{ $goal->id }}">
                    <form action="{{ route('solution.update', ['goal' => $goal->id, 'solution' => $user_solution->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <div class="col-12 col-sm-8">
                                <input class="form-control" type="text" value="{{ $user_solution->title }}" name="title" placeholder="Solution name...">
                            </div>

                            <div class="col-12 col-sm-4">
                                <input class="form-control btn btn-primary" type="submit" value="Submit A Solution">
                            </div>
                        </div>
                    </form>
                </div>
            @endif

        {{-- If the participant hasn't submitted a solution, let them add one --}}
        @elseif ($hunt->owner->id !== auth()->id())
            <div class="d-flex justify-content-between align-items-center">
                {{ $goal->title }}
                @if ($hunt->status === 'open')
                    <button class="border-0 bg-transparent" type="button" data-toggle="collapse" data-target="#goal-solution-form{{ $goal->id }}" aria-expanded="false" aria-controls="goal-solution-form{{ $goal->id }}">
                        <i class="fas fa-plus-circle" style="cursor: pointer;"></i>
                    </button>
                @endif
            </div>

            @if ($hunt->status === 'open')
                <div class="collapse mt-3" id="goal-solution-form{{ $goal->id }}">
                    <form action="{{ route('solution.store', ['goal' => $goal->id]) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col-12 col-sm-8">
                                <input class="form-control" type="text" name="title" placeholder="Solution name...">
                            </div>

                            <div class="col-12 col-sm-4">
                                <input class="form-control btn btn-primary" type="submit" value="Submit A Solution">
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        @endif
    </li>
@empty
    <li class="list-group-item"><em>This Scavenger Hunt does not have any goals yet.</em></li>
@endforelse