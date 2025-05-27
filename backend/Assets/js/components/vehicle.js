export const addVehicle = async (form) => {
    const data = new FormData(form)
    const response = await fetch('index.php?component=vehicles&action=addVehicle', {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
        method : 'POST',
        body : data
    })
    return await response.json()
}

export const deleteVehicle = async (id) => {
    const response = await fetch(`index.php?component=vehicles&action=deleteVehicle&id=${id}`, {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
    })
    return await response.json()
}

export const showUserVehicles = async (form) => {
    const data = new FormData (form)
    const response = await fetch('index.php?component=vehicles&action=showUserVehicles',{
        headers : {'X-Requested-With': 'XMLHttpRequest'},
        method : 'POST',
        body : data
    })
    return await response.json()
}

export const updateVehicle = async (form, id) => {
    const data = new FormData (form)
    const response = await fetch(`index.php?component=vehicles&action=updateVehicle&id=${id}`, {
        headers : {'X-Requested-With': 'XMLHttpRequest'},
        method : 'POST',
        body : data
    })
    return await response.json()
}