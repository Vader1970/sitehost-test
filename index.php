<?php
// **Note** API key should be in seperate .env file, and list .env in gitignore **
// Set the API server URL, API version, API key, and client ID
$apiServer = 'https://api.sitehost.nz'; // Base URL of the SiteHost API
$apiVersion = '1.0'; // Version of the SiteHost API
$apiKey = ''; // API key 
$clientID = ''; //Client ID

// Construct the API URL using the provided server, version, API key, and client ID
$apiURL = "{$apiServer}/{$apiVersion}/dns/list_domains.json?apikey={$apiKey}&client_id={$clientID}";

// Initialize cURL session
$ch = curl_init();

// Set cURL options for the request
curl_setopt($ch, CURLOPT_URL, $apiURL); // Set the URL for the cURL session
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the transfer as a string

// Make the API request and store the response
$response = curl_exec($ch);

// Get the HTTP status code
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check if the request was successful
if ($status === 200) {
    // Decode the JSON response into an associative array
    $domain_list = json_decode($response, true);

    // Check if the response contains domain information
    if (isset($domain_list['return'])) {
        // Output the list of domains
        echo "<h2>Domains for Customer #{$clientID}:</h2>";
        echo "<ul>";
        foreach ($domain_list['return'] as $domainInfo) {
            // Output each domain name, ensuring special characters are properly encoded
            echo "<li>" . htmlspecialchars($domainInfo['name']) . "</li>";
        }
        echo "</ul>";
    } else {
        // Output a message indicating no domains were found for the specified client ID
        echo "<p>No domains found for Customer #{$clientID}</p>";
    }
} else {
    // Output an error message if there was an issue accessing the API
    echo "<p>Error accessing the API (HTTP status code: {$status})</p>";
}

// Close the cURL session
curl_close($ch);

?>
