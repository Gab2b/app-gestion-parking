<?php
function loadRates(): array {
    $file = __DIR__ . '/../_partials/parkingInfos.php';
    if (!file_exists($file)) return [];
    return require $file;
}

function saveRates(array $data): bool {
    $export = var_export($data, true);
    $content = "<?php\nreturn $export;\n";
    $file = __DIR__ . '/../_partials/parkingInfos.php';
    return (bool) file_put_contents($file, $content);
}
