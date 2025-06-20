<link rel="stylesheet" href="frontend/css/dashboard.css">

<div class="container">
    <h1>Mon espace</h1>

    <h3>Bienvenue
        <?php
        if (preg_match("/^(.*)@/", $_SESSION['user_username'], $matches))
        {
        echo $matches[1];
        } else {
        echo $_SESSION['user_username'];
        }?></h3>
    <div>
        <div><a href="booked_spots">Mes réservations</a>
        <div class="placeholderForFunction">

        </div></div>
        <div><a href="vehicles">Mes véhicules</a>
        <div class="placeholderForFunction">

        </div></div>
        <div><a href="old_bookings">Mes réservations passées</a>
        <div class="placeholderForFunction">

        </div></div>
        <div><a href="checkout">Mon panier</a>
        <div>

        </div></div>
    </div>
</div>

<script src="backend/Assets/js/components/dashboard.js" type="module"></script>
<script type="module">
    import { getUserData } from "./backend/Assets/js/components/dashboard.js";

    document.addEventListener('DOMContentLoaded', async () => {

        const userData = await getUserData()
        const placeholders = document.querySelectorAll(".placeholderForFunction")
        console.log(userData)

        for (let i = 0; i < userData.length; i++) {
            if (userData[i].length >= 2) {
                for (let j=0; j<userData[i].length; j++) {
                    let newDiv = document.createElement("p");
                    console.log(userData[i][j])
                    let newContent = document.createTextNode(JSON.stringify(userData[i][j]))
                    newDiv.append(newContent)
                    placeholders[i].append(newDiv)
                }
            } else {
                placeholders[i].innerText = JSON.stringify(userData[i][0])
            }
        }
    })
</script>