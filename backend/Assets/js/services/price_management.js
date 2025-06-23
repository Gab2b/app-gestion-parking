export async function getRates() {
    const response = await fetch('index.php?component=price_management&action=getRates', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    return response.json();
}

export async function saveRates(rates) {
    const response = await fetch('index.php?component=price_management&action=saveRates', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(rates)
    });
    return response.json();
}
