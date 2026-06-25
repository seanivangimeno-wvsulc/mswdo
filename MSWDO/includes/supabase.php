<?php
// Supabase credentials - retrieves from env if set, otherwise falls back to placeholders
define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://YOUR_PROJECT_ID.supabase.co');
define('SUPABASE_KEY', getenv('SUPABASE_KEY') ?: 'YOUR_ANON_OR_SERVICE_ROLE_KEY');

/**
 * Make a REST API call to Supabase
 * @param string $table    - table name (e.g. 'tb_clients')
 * @param string $method   - GET, POST, PATCH, DELETE
 * @param array  $filters  - query params like ['email=eq.' . $email]
 * @param array  $body     - data to insert/update
 * @return array parsed JSON response
 */
function supabase_query($table, $method = 'GET', $filters = [], $body = null) {
    $url = SUPABASE_URL . '/rest/v1/' . $table;

    if (!empty($filters)) {
        // Clean up any leading ? or & in filters
        $url .= '?' . implode('&', $filters);
    }

    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return ['error' => true, 'message' => $err];
    }

    return json_decode($response, true);
}
?>
