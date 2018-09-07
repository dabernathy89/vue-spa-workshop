<form class="d-inline-block" action="{{ route('hunt.update', ['hunt' => $hunt->id]) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="closed">
    <button title="Close Scavenger Hunt" class="btn btn-secondary" type="submit">
        Close &nbsp;&nbsp;<i class="fas fa-door-closed"></i>
    </button>
</form>