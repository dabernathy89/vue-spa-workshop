<form class="d-inline-block" action="{{ route('hunt.delete', ['hunt' => $hunt->id]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button title="Delete Scavenger Hunt" class="btn btn-danger" type="submit">
        Delete &nbsp;&nbsp;<i class="fas fa-trash"></i>
    </button>
</form>