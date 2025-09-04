@if($translations->isEmpty())
    <div class="alert alert-warning">No translations found yet.</div>
@else
    @foreach($translations as $key => $t)
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Serial: {{ ($translations->currentPage() - 1) * $translations->perPage() + $loop->iteration }}</h5>

                <p><strong>Original (English):</strong><br>{{ $t->original_text }}</p>
                <p><strong>Translated (German):</strong><br>{{ $t->translated_text }}</p>

                @if($t->chassi)
                    <p><strong>Uploaded File:</strong>
                        <a href="{{ asset('storage/uploads/' . $t->chassi) }}" target="_blank">
                            {{ $t->chassi }}
                        </a>
                    </p>
                @endif

                @if($t->german_pdf)
                    <p><strong>Download Translated German PDF:</strong>
                        <a href="{{ asset('storage/generated/' . $t->german_pdf) }}" target="_blank" download>
                            {{ $t->german_pdf }}
                        </a>
                    </p>
                @endif

                <small class="text-muted">Submitted at: {{ $t->created_at->format('d M Y, h:i A') }}</small>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-center">
        {{ $translations->links('pagination::bootstrap-5') }}
    </div>
@endif
