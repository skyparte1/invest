import Chart from 'chart.js/auto';

const currencyFormatter = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
});

export function renderSimulationChart(dataElement) {
    const canvas = document.getElementById('simulation-chart');

    if (!canvas || !dataElement) {
        return;
    }

    const series = JSON.parse(dataElement.textContent);

    const palette = getComputedStyle(canvas);
    const color = (token) => palette.getPropertyValue(token).trim();
    const chart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: series.map((point) => `Mês ${point.month}`),
            datasets: [
                {
                    label: 'Total investido',
                    data: series.map((point) => point.invested),
                    borderColor: () => color('--muted'),
                    backgroundColor: () => color('--muted'),
                    borderDash: [6, 4],
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    tension: 0.18,
                },
                {
                    label: 'Saldo estimado',
                    data: series.map((point) => point.balance),
                    borderColor: () => color('--primary'),
                    backgroundColor: () => color('--primary'),
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    tension: 0.18,
                },
            ],
        },
        options: {
            responsive: true,
            animation: false,
            color: () => color('--ink'),
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { color: () => color('--line') },
                    ticks: { maxTicksLimit: 8, color: () => color('--muted') },
                },
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: () => color('--line') },
                    ticks: {
                        color: () => color('--muted'),
                        callback: (value) => currencyFormatter.format(value),
                    },
                },
            },
            plugins: {
                legend: {
                    labels: { color: () => color('--ink'), boxWidth: 24, boxHeight: 2 },
                },
                tooltip: {
                    backgroundColor: () => color('--ink'),
                    titleColor: () => color('--surface'),
                    bodyColor: () => color('--surface'),
                    callbacks: {
                        label: (context) => `${context.dataset.label}: ${currencyFormatter.format(context.parsed.y)}`,
                    },
                },
            },
        },
    });
    window.addEventListener('invest:themechange', () => chart.update('none'));
}
