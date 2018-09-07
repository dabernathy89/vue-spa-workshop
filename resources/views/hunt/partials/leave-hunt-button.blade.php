<form action="{{ route('hunt.remove_user', ['hunt' => $hunt->id, 'user' => auth()->id()]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button title="Leave Scavenger Hunt" class="btn btn-secondary" type="submit">Leave <i class="fas fa-sign-out-alt"></i></button>
</form>