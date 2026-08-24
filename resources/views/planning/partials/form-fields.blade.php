@php($goal = $goal ?? null)

<div class="mb-3">
    <label class="form-label" for="name">Nome da meta</label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $goal?->name) }}" maxlength="100" placeholder="Ex.: Reserva para viagem" required aria-describedby="name-help @error('name') name-error @enderror" @error('name') aria-invalid="true" @enderror>
    @error('name')<div class="invalid-feedback" id="name-error">{{ $message }}</div>@enderror
    <div class="form-text" id="name-help">Use um nome curto que identifique seu objetivo.</div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="target_amount">Valor-alvo</label>
        <div class="input-group has-validation">
            <span class="input-group-text">R$</span>
            <input class="form-control @error('target_amount') is-invalid @enderror" id="target_amount" name="target_amount" type="text" inputmode="decimal" value="{{ old('target_amount', $goal?->target_amount) }}" maxlength="20" placeholder="12.000,00" required @error('target_amount') aria-invalid="true" aria-describedby="target-amount-error" @enderror>
            @error('target_amount')<div class="invalid-feedback" id="target-amount-error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="current_amount">Valor já acumulado</label>
        <div class="input-group has-validation">
            <span class="input-group-text">R$</span>
            <input class="form-control @error('current_amount') is-invalid @enderror" id="current_amount" name="current_amount" type="text" inputmode="decimal" value="{{ old('current_amount', $goal?->current_amount ?? '0') }}" maxlength="20" placeholder="0,00" required @error('current_amount') aria-invalid="true" aria-describedby="current-amount-error" @enderror>
            @error('current_amount')<div class="invalid-feedback" id="current-amount-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="mt-3">
    <label class="form-label" for="target_date">Data-alvo</label>
    <input class="form-control @error('target_date') is-invalid @enderror" id="target_date" name="target_date" type="date" value="{{ old('target_date', $goal?->target_date?->format('Y-m-d')) }}" @if (! $goal) min="{{ now()->format('Y-m-d') }}" @endif required aria-describedby="date-help @error('target_date') date-error @enderror" @error('target_date') aria-invalid="true" @enderror>
    @error('target_date')<div class="invalid-feedback" id="date-error">{{ $message }}</div>@enderror
    <div class="form-text" id="date-help">@if ($goal)Datas anteriores são mantidas para que metas vencidas possam ser reorganizadas.@else A data deve ser hoje ou futura.@endif</div>
</div>

<div class="mt-3">
    <label class="form-label" for="description">Descrição <span class="optional-label">(opcional)</span></label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" maxlength="1000" placeholder="Anotações sobre o objetivo" @error('description') aria-invalid="true" aria-describedby="description-error" @enderror>{{ old('description', $goal?->description) }}</textarea>
    @error('description')<div class="invalid-feedback" id="description-error">{{ $message }}</div>@enderror
</div>
