<?php set_time_limit(0); header('Content-Type: text/html; charset=utf-8');

if (!extension_loaded('zip')) {
    die('Ошибка: Расширение ZIP не установлено на вашем хостинге.<br>Включите его или попросите это сделать поддержку хостинга.');
}
if (!extension_loaded('curl')) {
    die('Ошибка: Расширение CURL не установлено на вашем хостинге.<br>Включите его или попросите это сделать поддержку хостинга.');
}

if (isset($_GET['source'])) {
    $source_site = 'tulips2020'; // Название домена на Webflow без окончания webflow.io

    $source_path = dirname($_GET['source']);
    $source_file = $source_path.'/'.$source_site.'.zip';

    if (dirname(substr($source_path, strpos($source_path, 'webflow-converter.ru'))) === 'webflow-converter.ru') {
        $output_file = basename($source_file);

        curl_download($source_file, $output_file);

        $zip = new ZipArchive;
        $zip->open($output_file);
        $zip->extractTo('./');
        $zip->close();

        unlink($output_file);

        echo '<script>history.back();</script>';
    } else {
        die('Обновление невозможно. Проверьте настройку source_site в update.php!');
    }
}

function curl_download($url, $file)
{
    $dest_file = @fopen($file, "w");
    $resource = curl_init();
    curl_setopt($resource, CURLOPT_URL, $url);
    curl_setopt($resource, CURLOPT_FILE, $dest_file);
    curl_setopt($resource, CURLOPT_HEADER, 0);
    curl_exec($resource);
    curl_close($resource);
    fclose($dest_file);
}
