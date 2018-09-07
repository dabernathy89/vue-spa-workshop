<form action="{{ route('hunt.update', ['hunt' => $hunt->id]) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="winner_id" value="{{ $participant->id }}">
    <button title="Choose Winner" class="border-0 bg-transparent" type="submit"><i class="fas fa-trophy"></i></button>
</form>