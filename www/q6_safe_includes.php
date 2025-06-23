<?php
// Define the base directory for included pages
$pages_dir = 'pages/';
// Define the default page to include if none is specified or if an invalid one is requested
$default_page = 'home.php';
// Define a whitelist of allowed pages to prevent directory traversal and arbitrary file inclusion
// This is the most secure method. Add all your valid page names here without the .php extension.
$allowed_pages = [
    'home',
    'about',
    'contact',
    'admin', // Example: only allow 'admin' if the user is authenticated and authorized
    'products'
];

// Function to safely include a page
function safely_include_page($page_name, $pages_directory, $allowed_list, $default_page_name) {
    // 1. Sanitize the input: Remove any characters that are not alphanumeric, hyphens, or underscores.
    // This helps prevent directory traversal attempts like '..%2F' or '..\'
    $sanitized_page_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $page_name);

    // 2. Check if the sanitized page name is in the whitelist of allowed pages.
    if (!in_array($sanitized_page_name, $allowed_list)) {
        // If not in the whitelist, fall back to the default page.
        $sanitized_page_name = str_replace('.php', '', $default_page_name); // Ensure default name is also without extension for lookup
        if (!in_array($sanitized_page_name, $allowed_list)) {
             // Fallback default page not in list? This should not happen if default_page is chosen correctly.
            error_log("Default page '{$default_page_name}' not in allowed list. Please check configuration.");
            echo "<div style='color: red; padding: 10px; border: 1px solid red; background-color: #ffe0e0; margin-bottom: 15px; border-radius: 5px;'>A critical configuration error occurred. Please contact support.</div>";
            return;
        }
    }

    // 3. Construct the full path to the file.
    // Ensure that the path is absolute or relative to a known secure base.
    $file_path = $pages_directory . $sanitized_page_name . '.php';

    // 4. Canonicalize the path to resolve any '..' or '.' components.
    // realpath() returns the absolute pathname, or false on failure (e.g., file doesn't exist).
    $real_file_path = realpath($file_path);

    // 5. Verify that the resolved path actually starts with the base pages directory path.
    // This is a crucial check to prevent directory traversal even if realpath() works.
    // Also, ensure realpath() didn't return false (meaning the file doesn't exist).
    if ($real_file_path !== false && strpos($real_file_path, realpath($pages_directory)) === 0) {
        // 6. Check if the file exists and is readable.
        if (file_exists($real_file_path) && is_readable($real_file_path)) {
            // 7. Include the file.
            include $real_file_path;
        } else {
            // Log the error for debugging purposes (optional, but good practice)
            error_log("Failed to include: File not found or not readable at " . $real_file_path);
            echo "<div style='color: red; padding: 10px; border: 1px solid red; background-color: #ffe0e0; margin-bottom: 15px; border-radius: 5px;'>Error: The requested page could not be loaded.</div>";
        }
    } else {
        // Log the error for debugging purposes
        error_log("Attempted directory traversal or invalid path: " . $file_path);
        echo "<div style='color: orange; padding: 10px; border: 1px solid orange; background-color: #fff8e0; margin-bottom: 15px; border-radius: 5px;'>Warning: Invalid page request. Displaying default content.</div>";
        // Optionally include the default page here as a final fallback for security
        // Note: The main logic already falls back to default if not in allowed_list,
        // this is an extra layer if realpath() issues are detected.
        // If you reach here, it implies a suspicious path. It's often safer to just
        // show an error or a generic "page not found" message.
        include realpath($pages_directory . $default_page_name);
    }
}

// Get the requested page from the URL (GET parameter)
$requested_page = isset($_GET['page']) ? $_GET['page'] : str_replace('.php', '', $default_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Page Loader</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8; /* Light blue-gray background */
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        nav a {
            padding: 8px 15px;
            background-color: #3b82f6; /* Blue-600 */
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        nav a:hover {
            background-color: #2563eb; /* Blue-700 */
        }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <header class="mb-8">
            <h1 class="text-4xl font-bold text-center text-gray-800 mb-4">Welcome to Our Site!</h1>
            <nav class="flex justify-center space-x-4">
                <a href="?page=home" class="hover:bg-blue-700">Home</a>
                <a href="?page=about" class="hover:bg-blue-700">About Us</a>
                <a href="?page=contact" class="hover:bg-blue-700">Contact</a>
                <a href="?page=products" class="hover:bg-blue-700">Products</a>
                <!-- This 'admin' link should only be shown if user is authenticated/authorized -->
                <a href="?page=admin" class="hover:bg-blue-700">Admin (Example)</a>
            </nav>
        </header>

        <main class="border-t pt-8 border-gray-200">
            <?php
            // Call the function to safely include the requested page
            safely_include_page($requested_page, $pages_dir, $allowed_pages, $default_page);
            ?>
        </main>

        <footer class="mt-8 text-center text-gray-600 text-sm border-t pt-4 border-gray-200">
            <p>&copy; <?php echo date("Y"); ?> My Awesome Website. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>

<?php
// --- Directory for pages (Create this directory and these files) ---
// Structure:
// your_script.php
// pages/
//   home.php
//   about.php
//   contact.php
//   admin.php
//   products.php
?>

<?php
// Example content for pages/home.php
/*
<div class="text-center">
    <h2 class="text-3xl font-semibold mb-4">Home Page</h2>
    <p class="text-lg">Welcome to the home page of our dynamic website!</p>
    <p>This content is loaded dynamically from <code>pages/home.php</code>.</p>
</div>
*/
?>

<?php
// Example content for pages/about.php
/*
<div class="text-center">
    <h2 class="text-3xl font-semibold mb-4">About Us</h2>
    <p class="text-lg">We are a company dedicated to providing the best solutions.</p>
    <p>Learn more about our mission and values.</p>
</div>
*/
?>

<?php
// Example content for pages/contact.php
/*
<div class="text-center">
    <h2 class="text-3xl font-semibold mb-4">Contact Us</h2>
    <p class="text-lg">Have questions? Reach out to us!</p>
    <p>Email: info@example.com</p>
</div>
*/
?>

<?php
// Example content for pages/admin.php (add authentication checks in a real app)
/*
<div class="text-center bg-red-100 p-6 rounded-lg border border-red-300">
    <h2 class="text-3xl font-semibold mb-4 text-red-800">Admin Panel (Requires Authentication!)</h2>
    <p class="text-lg text-red-700">This page would typically be restricted to authorized users.</p>
    <p class="text-red-600">Accessing this without proper authentication could be a security risk in a real application.</p>
</div>
*/
?>
