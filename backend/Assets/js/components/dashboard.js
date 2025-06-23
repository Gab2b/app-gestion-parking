export const getUserData = async () => {
    const response = await fetch(`index.php?component=dashboard`, {
        headers: {'X-Requested-With': 'XMLHttpRequest'},
    })
    return await response.json()
}