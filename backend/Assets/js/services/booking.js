export const calculatePrice = async (startingDate, endingDate, startingHour, endingHour) => {
    const response = await fetch(`index.php?component=booking&action=calculatePrice&startingDate=${startingDate}&endingDate=${endingDate}&startingHour=${startingHour}&endingHour=${endingHour}`, {
        headers: {'X-Requested-With': 'XMLHttpRequest'},
    })
    return await response.json()
}

export const checkHourly = async (formData) => {
    const response = await fetch(`index.php?component=booking&action=bookASpot`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    return await response.json()
}

export const reserveSpot = async (formData, spotId) => {
    const response = await fetch(`index.php?component=booking&action=holdASpot&spot=${spotId}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    return await response.json()
}

export const reservePaidSpot = async (formData, spotId) => {
    formData.append("isPaid", true)

    const response = await fetch(`index.php?component=booking&action=holdASpot&spot=${spotId}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    return await response.json()
}