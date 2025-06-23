export const getProfile = async () => {
    const response = await fetch('index.php?component=profile&action=getProfile',
        { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
    return response.json();
};

export const updateProfile = async (formData) => {
    const response = await fetch('index.php?component=profile&action=updateProfile', {
        method : 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body   : formData
    });
    return response.json();
};

export async function saveRates(rates) {
    const response = await fetch('?action=saveRates', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(rates)
    });

    return response.json();
}
