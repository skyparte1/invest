@props(['investment'])

<span class="risk-badge risk-{{ $investment->risk_level }}">Risco: {{ $investment->riskLabel() }}</span>
