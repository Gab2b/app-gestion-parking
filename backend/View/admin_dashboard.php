<link rel="stylesheet" href="frontend/css/admin_dashboard.css">

<div class="container">
    <h1>Dashboard Administrateur</h1>

    <div>
        <table border="1">
        <thead>
        <tr>
            <th>Numéro de place</th>
            <th>Disponibilité</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($spotsStatus as $spot): ?>
            <tr>
                <td><?= htmlspecialchars($spot['number']) ?></td>
                <td style="color: <?= $spot['is_free'] ? 'green' : 'red' ?>;">
                    <?= $spot['is_free'] ? 'Disponible' : 'Occupée' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    <div>
        <div><a href="price_management">Gestion des tarifs</a></div>
        <div><a href="spots_management">Gestion des places</a></div>

    </div>
</div>