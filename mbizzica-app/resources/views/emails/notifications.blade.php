@component('mail::message')
# Paste in Scadenza

Ciao {{ $user->name }},

Il seguente paste sta per scadere:

**Titolo:** {{ $paste->title }}

**Contenuto:**
{{ $paste->content }}

@if ($paste->file)
**File Allegato:** [Scarica il file]({{ Storage::disk('local')->url($paste->file) }})
@endif

Scadrà il: {{ $paste->expires_at }}

@component('mail::button', ['url' => route('pastes.take', ['id' => $paste->id, 'slug' => Str::slug($paste->title)])])
Visualizza il Paste
@endcomponent

Grazie,<br>
Il tuo team di {{ config('app.name') }}
@endcomponent
