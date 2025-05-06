export const login = async (emailAddress, password) => {
    console.log(emailAddress, password)
    const response = await fetch('index.php?component=login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            email: emailAddress,
            passcode: password
        })
    })
    const jsonResponse = await response.json();
    return jsonResponse;
}