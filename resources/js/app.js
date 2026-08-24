import * as bootstrap from 'bootstrap';

document.querySelectorAll('#mainNav .nav-link, #mainNav .nav-actions a').forEach((link) => {
    link.addEventListener('click', () => {
        const navigation = document.getElementById('mainNav');

        if (navigation.classList.contains('show')) {
            bootstrap.Collapse.getOrCreateInstance(navigation).hide();
        }
    });
});

const simulationChartData = document.getElementById('simulation-chart-data');

if (simulationChartData) {
    import('./simulator').then(({ renderSimulationChart }) => renderSimulationChart(simulationChartData));
}
