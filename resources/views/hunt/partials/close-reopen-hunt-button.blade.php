<form class="d-inline-block" action="{{ route('hunt.update', ['hunt' => $hunt->id]) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $hunt->isOpen ? 'closed' : 'open' }}">
    <button title="Close Scavenger Hunt" class="btn btn-secondary" type="submit">
        @if ($hunt->isOpen)
            Close &nbsp;&nbsp;<i class="fas fa-door-closed"></i>
        @else
            Reopen &nbsp;&nbsp;<i class="fas fa-door-open"></i>
        @endif
    </button>
</form>