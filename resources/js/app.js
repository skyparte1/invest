import * as bootstrap from 'bootstrap';

document.querySelectorAll('#mainNav .nav-link, #mainNav .nav-actions a').forEach((link) => {
    link.addEventListener('click', () => {
        const navigation = document.getElementById('mainNav');

        if (navigation.classList.contains('show')) {
            bootstrap.Collapse.getOrCreateInstance(navigation).hide();
        }
    });
});

document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-disable-on-submit]');
    const submitter = event.submitter;

    if (!form || !submitter || event.defaultPrevented || submitter.disabled) {
        return;
    }

    submitter.disabled = true;
    submitter.dataset.submitLocked = 'true';
    submitter.setAttribute('aria-disabled', 'true');
});

window.addEventListener('pageshow', () => {
    document.querySelectorAll('[data-submit-locked="true"]').forEach((submitter) => {
        submitter.disabled = false;
        delete submitter.dataset.submitLocked;
        submitter.removeAttribute('aria-disabled');
    });
});

const simulationChartData = document.getElementById('simulation-chart-data');

if (simulationChartData) {
    import('./simulator').then(({ renderSimulationChart }) => renderSimulationChart(simulationChartData));
}
