export function bindReturnReference(form) {
    const { annual_rate: rate, investment_slug: modality, reference_id: reference } = form.elements;
    const status = form.querySelector('#reference-status');
    const clear = () => {
        reference.value = '';
        status.textContent = '';
    };
    modality.addEventListener('change', clear);
    rate.addEventListener('input', clear);
    form.querySelectorAll('[data-reference]').forEach((button) => {
        button.addEventListener('click', () => {
            rate.value = button.dataset.rate.replace('.', ',');
            reference.value = button.dataset.reference;
            status.textContent = 'Referência aplicada: ' + rate.value + '% ao ano.';
        });
    });
}
