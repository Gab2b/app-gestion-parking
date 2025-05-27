<div class="container">
    <form class="payment-form">
        <h2>Paiement</h2>

        <div>
            <div>
                <label for="name">Nom sur la carte</label>
                <input type="text" id="name" placeholder="Jean Dupont" required>
            </div>

            <div>
                <label for="card-number">Numéro de carte</label>
                <input type="text" id="card-number" placeholder="1234 5678 9012 3456" required>
            </div>

            <div>
                <label for="expiry">Date d'expiration</label>
                <input type="text" id="expiry" placeholder="MM/AA" required>
            </div>

            <div>
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" placeholder="123" required>
            </div>

            <button type="submit">Payer par carte</button>
        </div>

        <div>
            <a href="https://www.paypal.com/paypalme/" class="paypal-btn" target="_blank">Payer avec PayPal</a>
        </div>
    </form>
</div>