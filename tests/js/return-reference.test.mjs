import test from 'node:test';
import assert from 'node:assert/strict';
import { bindReturnReference } from '../../resources/js/return-reference.js';

test('only explicit application changes the rate; edits and modality changes clear attribution', () => {
    const rate = Object.assign(new EventTarget(), { value: '-2,5' });
    const modality = new EventTarget();
    const reference = { value: '' };
    const button = Object.assign(new EventTarget(), { dataset: { reference: 'di', rate: '13.65' } });
    const status = {};
    bindReturnReference({
        elements: { annual_rate: rate, investment_slug: modality, reference_id: reference },
        querySelector: () => status,
        querySelectorAll: () => [button],
    });
    modality.dispatchEvent(new Event('change'));
    assert.equal(rate.value, '-2,5');
    button.dispatchEvent(new Event('click'));
    assert.equal(rate.value, '13,65');
    assert.equal(reference.value, 'di');
    rate.value = '-5';
    rate.dispatchEvent(new Event('input'));
    assert.equal(reference.value, '');
    button.dispatchEvent(new Event('click'));
    modality.dispatchEvent(new Event('change'));
    assert.equal(reference.value, '');
    assert.equal(rate.value, '13,65');
});
