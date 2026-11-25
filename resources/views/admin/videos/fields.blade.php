<label class="form-label" for="url">Link do YouTube</label>
<input class="form-control @error('url') is-invalid @enderror" type="url" id="url" name="url" value="{{ old('url', $video->watchUrl ?? null) }}" aria-describedby="video-help">
@error('url')<div class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
<p id="video-help" class="form-text">Cole o link do vídeo. Para começar em um trecho, use um link com o tempo em segundos. @isset($video)Para remover o vídeo, apague o link e salve.@endisset</p>
<label class="form-label" for="author">Autor ou canal (opcional)</label>
<input class="form-control @error('author') is-invalid @enderror" id="author" name="author" maxlength="255" value="{{ old('author', $video->author ?? null) }}">
@error('author')<div class="invalid-feedback" role="alert">{{ $message }}</div>@enderror
