<form action="{{ route('hunt.goal.delete', ['hunt' => $hunt->id, 'goal' => $goal->id]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button title="Delete Goal" class="border-0 bg-transparent" type="submit"><i class="fas fa-trash"></i></button>
</form>