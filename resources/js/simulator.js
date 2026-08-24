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

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: series.map((point) => `Mês ${point.month}`),
            datasets: [
                {
                    label: 'Total investido',
                    data: series.map((point) => point.invested),
                    borderColor: '#6c7b91',
                    backgroundColor: 'rgba(108, 123, 145, 0.08)',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    tension: 0.18,
                },
                {
                    label: 'Saldo estimado',
                    data: series.map((point) => point.balance),
                    borderColor: '#1857c9',
                    backgroundColor: 'rgba(24, 87, 201, 0.10)',
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    tension: 0.18,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { maxTicksLimit: 12 },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => currencyFormatter.format(value),
                    },
                },
            },
            plugins: {
                legend: {
                    labels: { usePointStyle: true },
                },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.dataset.label}: ${currencyFormatter.format(context.parsed.y)}`,
                    },
                },
            },
        },
    });
}
