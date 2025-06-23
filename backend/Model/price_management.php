<?php
function loadRates(): array
{
$file = __DIR__ . '/../_partials/parkingInfos.php';  // ✅ bon chemin
return file_exists($file) ? include $file : [];
}

function saveRates(array $data): bool
{
$file = __DIR__ . '/../_partials/parkingInfos.php';  // ✅ bon chemin

$export = "<?php\nreturn " . var_export($data, true) . ";\n";

    if (!is_writable($file) && !is_writable(dirname($file))) {
        error_log("price_management - NO WRITE PERMISSION on $file");
        return false;
    }

    $tmp = tempnam(sys_get_temp_dir(), 'rates_');
    if ($tmp === false || file_put_contents($tmp, $export, LOCK_EX) === false) {
        error_log("price_management - cannot write temp file");
        return false;
    }

    if (!@rename($tmp, $file)) {
        @unlink($file);
        if (!@rename($tmp, $file)) {
            @unlink($tmp);
            error_log("price_management - rename() failed for $file");
            return false;
        }
    }

    return true;
}
