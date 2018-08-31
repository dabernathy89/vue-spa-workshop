@forelse ($hunt->goals as $goal)
    <li class="list-group-item">
        @if ($hunt->status === 'open')
            <div class="d-flex justify-content-between align-items-center">
                <strong>{{ $goal->title }}</strong>
                <form action="{{ route('hunt.goal.delete', ['hunt' => $hunt->id, 'goal' => $goal->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button title="Delete Goal" class="border-0 bg-transparent" type="submit"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        @else
            <p><strong>{{ $goal->title }}</strong></p>
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
    </li>
@empty
    <li class="list-group-item"><em>This Scavenger Hunt does not have any goals yet.</em></li>
@endforelse