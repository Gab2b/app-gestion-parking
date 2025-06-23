export const payReservation = async (reservationId) => {
    const formData = new FormData()
    formData.append('reservationId', reservationId)

    const response = await fetch('index.php?component=checkout&action=payOne', {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
        body : formData,
        method : 'POST'
    })
    return await response.json()
}

export const payAllReservations = async () => {
    const response = await fetch(`index.php?component=checkout&action=payAll`, {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
        method : 'POST'
    })
    return await response.json()
}


export const getUserVehicles = async () => {
    const response = await fetch(`index.php?component=checkout&action=getVehicles`, {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
    })
    return await response.json()
}

export const getAvailableSpots = async (formData, spotId) => {
    formData.append('oldSpotId', spotId)
    const response = await fetch(`index.php?component=checkout&action=getAvailableSpots`, {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
        method : 'POST',
        body: formData
    })
    return await response.json()
}

export const updateReservation = async (formData) => {
    const response = await fetch(`index.php?component=checkout&action=updateReservation`, {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
        method : 'POST',
        body: formData
    })
    return await response.json()
}

export const deleteReservation = async (reservationId) => {
    const formData = new FormData()
    formData.append('reservationId', reservationId)
    const response = await fetch(`index.php?component=checkout&action=deleteReservation`, {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
        method : 'POST',
        body: formData
    })
    return await response.json()
}