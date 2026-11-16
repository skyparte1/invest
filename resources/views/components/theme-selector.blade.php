<label class="theme-selector" for="theme-preference">
    <span class="visually-hidden">Tema de aparência</span>
    @foreach (['system', 'light', 'dark'] as $mode)
        <x-icon :name="$mode" :data-theme-icon="$mode" />
    @endforeach
    <select id="theme-preference" class="form-select form-select-sm">
        <option value="system">Sistema</option>
        <option value="light">Claro</option>
        <option value="dark">Escuro</option>
    </select>
</label>
