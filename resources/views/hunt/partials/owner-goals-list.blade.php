@forelse ($hunt->goals as $goal)
    <li class="list-group-item">
        @if ($hunt->isOpen)
            <div class="d-flex justify-content-between align-items-center">
                <strong>{{ $goal->title }}</strong>
                @include('hunt.partials.delete-goal-button')
            </div>
        @else
            <strong>{{ $goal->title }}</strong>
        @endif
    </li>
@empty
    <li class="list-group-item"><em>This Scavenger Hunt does not have any goals yet.</em></li>
@endforelse