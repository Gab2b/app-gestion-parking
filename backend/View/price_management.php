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

        let rates;
        try { rates = await getRates(); }
        catch { Swal.fire('Erreur', 'Impossible de charger les tarifs', 'error'); return; }

        const tbody = document.getElementById('ratesBody');
        const form = document.getElementById('ratesForm');

        function addTrancheRow (container, from = '00:00', to = '23:59', price = 0) {
            if (to === '24:00') { to = '23:59'; }

            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
      <input type="time"   class="form-control start" value="${from}">
      <input type="time"   class="form-control end"   value="${to}">
      <input type="number" class="form-control price" value="${price}" min="0">
      <button type="button" class="btn btn-outline-danger btn-remove-tranche">−</button>
    `;
            container.appendChild(div);

            div.querySelector('.btn-remove-tranche').addEventListener('click', () => div.remove());
        }

        function render () {
            tbody.innerHTML = '';

            rates.parking_rate.forEach((dayObj, idx) => {
                const isFixed = !Array.isArray(dayObj.prices);

                const tr = document.createElement('tr');
                tr.innerHTML = `
        <td><strong>${dayObj.day}</strong></td>

        <td>
          <div class="form-check form-switch mb-2">
            <input class="form-check-input js-mode"
                   type="checkbox"
                   id="mode${idx}"
                   ${isFixed ? 'checked' : ''}>
            <label class="form-check-label" for="mode${idx}">Tarif fixe</label>
          </div>

          <div class="js-fixed ${isFixed ? '' : 'd-none'}">
            <input type="number" min="0" class="form-control" value="${isFixed ? dayObj.prices : ''}">
          </div>

          <div class="js-tranches ${isFixed ? 'd-none' : ''}">
            <div data-container="tranches"></div>
            <button type="button"
                    class="btn btn-sm btn-secondary btn-add-tranche mt-1">
              + tranche
            </button>
          </div>
        </td>

        <td>
          <button type="button"
                  class="btn btn-sm btn-outline-danger btn-remove-day">
            ×
          </button>
        </td>
      `;
                tbody.appendChild(tr);

                if (!isFixed) {
                    const container = tr.querySelector('[data-container="tranches"]');
                    dayObj.prices.forEach(t => addTrancheRow(container, t.from, t.to, t.price));
                }

                tr.querySelector('.js-mode').addEventListener('change', e => {
                    const fixed = tr.querySelector('.js-fixed');
                    const tranche = tr.querySelector('.js-tranches');
                    fixed  .classList.toggle('d-none', !e.target.checked);
                    tranche.classList.toggle('d-none',  e.target.checked);
                });

                tr.querySelector('.btn-add-tranche').addEventListener('click', () => {
                    addTrancheRow(tr.querySelector('[data-container="tranches"]'));
                });

                tr.querySelector('.btn-remove-day').addEventListener('click', () => {
                    rates.parking_rate.splice(idx, 1);
                    render();
                });
            });
        }

        render();

        form.addEventListener('submit', async evt => {
            evt.preventDefault();

            rates.parking_rate.forEach((dayObj, i) => {
                const tr = tbody.children[i];
                const isFixed = tr.querySelector('.js-mode').checked;

                if (isFixed) {
                    const val = parseFloat(tr.querySelector('.js-fixed input').value) || 0;
                    dayObj.prices = val;
                } else {
                    const arr = [];
                    tr.querySelectorAll('div.input-group').forEach(div => {
                        const from = div.querySelector('.start').value;
                        const toRaw = div.querySelector('.end').value;
                        const to = (toRaw === '24:00') ? '23:59' : toRaw;
                        const price = parseFloat(div.querySelector('.price').value) || 0;
                        arr.push({ from, to, price });
                    });
                    dayObj.prices = arr;
                }
            });

            const res = await saveRates(rates);

            if (res.success) {
                Swal.fire('Prix modifiés !', '', 'success');
            } else {
                Swal.fire('Erreur !', '', 'error');
            }

        });
    });
</script>
