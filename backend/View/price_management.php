<link rel="stylesheet" href="frontend/css/price_management.css"

<br>
<h1>Tarifs personnalisés</h1>
<form id="ratesForm">
    <table class="table">
        <thead>
        <tr>
            <th>Jour</th>
            <th>Tarif (fixe ou tranche)</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="ratesBody"></tbody>
    </table>
    <button type="submit" class="btn btn-success">Sauvegarder</button>
</form>

<script src="frontend/assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
<script src="frontend/assets/sweetalert2-11.22.0/dist/sweetalert2.all.min.js"></script>
<script type="module">
    import { getRates, saveRates } from "./backend/Assets/js/services/price_management.js";

    document.addEventListener('DOMContentLoaded', async () => {
        const body = document.getElementById('ratesBody');
        let rates = await getRates();

        function render() {
            body.innerHTML = '';
            rates.parking_rate.forEach((dayObj, i) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
          <td><strong>${dayObj.day}</strong></td>
          <td>
            <div class="form-check form-switch">
              <input class="form-check-input js-mode" type="checkbox" id="mode${i}" ${Array.isArray(dayObj.prices) ? '' : 'checked'}>
              <label class="form-check-label" for="mode${i}">Fixe ?</label>
            </div>
            <div class="js-fixed ${Array.isArray(dayObj.prices) ? 'd-none' : ''}">
              <input type="number" class="form-control" value="${dayObj.prices}" min="0">
            </div>
            <div class="js-tranches ${Array.isArray(dayObj.prices) ? '' : 'd-none'}">
              <div data-container="tranches"></div>
              <button type="button" class="btn btn-sm btn-secondary btn-add-tranche">+ tranche</button>
            </div>
          </td>
          <td><button type="button" class="btn btn-sm btn-danger btn-remove-day">Supprimer</button></td>
        `;
                body.appendChild(tr);

                // initial load of tranches
                if (Array.isArray(dayObj.prices)) {
                    const container = tr.querySelector('[data-container="tranches"]');
                    dayObj.prices.forEach((t, ti) => {
                        const div = document.createElement('div');
                        div.classList.add('input-group', 'mb-2');
                        div.innerHTML = `
              <input type="time" class="form-control start" value="${t.from}">
              <input type="time" class="form-control end"   value="${t.to}">
              <input type="number" class="form-control price" value="${t.price}" min="0">
              <button type="button" class="btn btn-outline-danger btn-remove-tranche">−</button>
            `;
                        container.appendChild(div);
                    });
                }

                tr.querySelector('.js-mode').addEventListener('change', (e) => {
                    tr.querySelector('.js-fixed').classList.toggle('d-none', !e.target.checked);
                    tr.querySelector('.js-tranches').classList.toggle('d-none', e.target.checked);
                });

                tr.querySelector('.btn-add-tranche').addEventListener('click', () => {
                    const container = tr.querySelector('[data-container="tranches"]');
                    const div = document.createElement('div');
                    div.classList.add('input-group', 'mb-2');
                    div.innerHTML = `
            <input type="time" class="form-control start" value="00:00">
            <input type="time" class="form-control end"   value="24:00">
            <input type="number" class="form-control price" value="0" min="0">
            <button type="button" class="btn btn-outline-danger btn-remove-tranche">−</button>
          `;
                    container.appendChild(div);
                    div.querySelector('.btn-remove-tranche').addEventListener('click', () => div.remove());
                });

                tr.querySelectorAll('.btn-remove-tranche').forEach(btn => btn.addEventListener('click', (e) => e.target.closest('div.input-group').remove()));
            });
        }

        render();

        document.getElementById('ratesForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            // Lire les valeurs modifiées
            rates.parking_rate.forEach((dayObj, i) => {
                const tr = body.children[i];
                const isFixed = tr.querySelector('.js-mode').checked;
                if (isFixed) {
                    const val = parseFloat(tr.querySelector('.js-fixed input')?.value || 0);
                    dayObj.prices = val;
                } else {
                    const arr = [];
                    tr.querySelectorAll('div.input-group').forEach(div => {
                        const from = div.querySelector('.start').value;
                        const to   = div.querySelector('.end').value;
                        const p    = parseFloat(div.querySelector('.price').value);
                        arr.push({from, to, price: p});
                    });
                    dayObj.prices = arr;
                }
            });

            const res = await saveRates(rates);
            if (res.success) {
                Swal.fire('✅ Sauvegardé', '', 'success');
            } else {
                Swal.fire('❌ Erreur', res.error || 'Impossible d’écrire le fichier', 'error');
            }
        });
    });
</script>
