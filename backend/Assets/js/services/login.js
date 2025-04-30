export const login = async (emailAddress, password) => {
    console.log(emailAddress, password)
    const response = await fetch('index.php?component=login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            email: emailAddress,
            passcode: password
        })
    })
    const text = await response.text();
    console.log(text);
}