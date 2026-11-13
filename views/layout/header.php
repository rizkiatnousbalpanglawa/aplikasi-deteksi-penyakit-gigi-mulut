<?php
// Layout atas untuk halaman user. Variabel opsional: $title, $bodyAttrs.
$title = isset($title) ? $title : 'Diagnosa Penyakit Gigi dan Mulut';
$bodyAttrs = isset($bodyAttrs) ? $bodyAttrs : 'class="bg-putih"';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha512-rt/SrQ4UNIaGfDyEXZtNcyWvQeOq0QLygHluFQcSjaGB04IxWhal71tKuzP6K8eYXYB6vJV4pHkXcmFGGQ1/0w==" crossorigin="anonymous">
    <!-- Custom Fonts -->
    <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css">
</head>

<body <?= $bodyAttrs ?>>
    <div class="preloader">
        <div class="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <?= flash_html() ?>
