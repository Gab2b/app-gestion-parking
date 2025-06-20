export const getAllSpots = async () => {
    const response = await fetch('index.php?component=spots_management&action=getAllSpots', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    return await response.json();
}

export const addSpot = async (type) => {
    const formData = new FormData();
    formData.append('type', type);

    const response = await fetch('index.php?component=spots_management&action=createSpot', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        method: 'POST',
        body: formData
    });
    return await response.json();
}

export const updateSpot = async (id, type) => {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('type', type);

    const response = await fetch('index.php?component=spots_management&action=updateSpot', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        method: 'POST',
        body: formData
    });
    return await response.json();
}

export const deleteSpot = async (id) => {
    const formData = new FormData();
    formData.append('id', id);

    const response = await fetch('index.php?component=spots_management&action=deleteSpot', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        method: 'POST',
        body: formData
    });
    return await response.json();
}
