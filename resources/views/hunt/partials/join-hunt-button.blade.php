<form action="{{ route('hunt.add_user', ['hunt' => $hunt->id, 'user' => auth()->id()]) }}" method="POST">
    @csrf
    <button title="Join Scavenger Hunt" class="btn btn-secondary" type="submit">Join <i class="fas fa-user-plus"></i></button>
</form>