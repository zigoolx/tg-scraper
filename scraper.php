<?php
/**
 * Telegram Scraper for GitHub Actions
 * Runs on a schedule, scrapes channels, and saves the raw HTML.
 */

// Define the exact channel usernames you want to keep updated
$channels = [
    'vahidonline',
    'vahidoonline',
    'mamlekate',
    'IranintlTV',
    'ManotoTV',
    'configraygan',
    'persianvpnhub',
    'jimjim8836',
    'wiki_tajrobe',
    'SamnetInternet'
];

$data_dir = __DIR__ . '/data';

if (!is_dir($data_dir)) {
    mkdir($data_dir, 0755, true);
}

foreach ($channels as $channel) {
    $url = "https://t.me/s/" . $channel;
    
    echo "Scraping @{$channel}...\n";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    // Masquerade as a standard browser
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
    
    $html = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($html !== false && $http_status === 200) {
        file_put_contents($data_dir . "/{$channel}.html", $html);
        echo "Successfully saved {$channel}.html\n";
    } else {
        echo "Failed to scrape {$channel}. HTTP Status: {$http_status}\n";
    }
    
    // Slight delay to avoid hitting rate limits too quickly
    sleep(2);
}
echo "Scraping complete.\n";
