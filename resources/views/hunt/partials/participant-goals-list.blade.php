@forelse ($hunt->goals as $goal)
    <li class="list-group-item">
        {{-- If the participant has submitted a solution, display it --}}
        @php
            $user_solution = $goal->solutions->where('user_id', auth()->id())->first();
        @endphp
        <div class="d-flex justify-content-between align-items-center">
            {{ $goal->title }}

            @if ($hunt->isOpen)
                <button class="border-0 bg-transparent" type="button" data-toggle="collapse" data-target="#goal-solution-form{{ $goal->id }}" aria-expanded="false" aria-controls="goal-solution-form{{ $goal->id }}">
                    <i class="fas {{ $user_solution ? 'fa-edit' : 'fa-plus-circle' }}" style="cursor: pointer;"></i>
                </button>
            @endif
        </div>

        @if ($user_solution)
            <div class="alert alert-secondary mt-2 d-flex justify-content-between align-items-start">
                <span>Your solution: </span><img style="height: auto; width: 50%;" src="{{ asset($user_solution->image) }}">
            </div>
        @endif

        {{-- The form for creating or updating a solution --}}
        @if ($hunt->isOpen)
            <div class="collapse mt-3" id="goal-solution-form{{ $goal->id }}">
                @php
                    $route = empty($user_solution) ? 'solution.store' : 'solution.update';
                    $args = empty($user_solution) ? ['goal' => $goal->id] : ['goal' => $goal->id, 'solution' => $user_solution->id];
                @endphp
                <form action="{{ route($route, $args) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (!empty($user_solution))
                        @method('PATCH')
                    @endif
                    <div class="form-row">
                        <div class="col-12 col-sm-8">
                            <input class="form-control-file" type="file" id="solution-image-{{ $goal->id }}" name="image">
                        </div>

                        <div class="col-12 col-sm-4">
                            <input class="form-control btn btn-primary" type="submit" value="{{ empty($user_solution) ? 'Submit A Solution' : 'Update Solution' }}">
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </li>
@empty
    <li class="list-group-item"><em>This Scavenger Hunt does not have any goals yet.</em></li>
@endforelse