<?php
// messages.txt will be created in the same directory as this PHP script.
$filename = 'messages.txt';

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data); // Remove whitespace from the beginning and end of string
    $data = stripslashes($data); // Remove backslashes
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); // Convert special characters to HTML entities
    return $data;
}

// Check if the form has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to hold errors
    $errors = [];

    // Sanitize and validate Name
    if (empty($_POST["name"])) {
        $errors[] = "Name is required.";
    } else {
        $name = sanitize_input($_POST["name"]);
        // Additional validation if needed, e.g., name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $errors[] = "Only letters and white space allowed in name.";
        }
    }

    // Sanitize and validate Email
    if (empty($_POST["email"])) {
        $errors[] = "Email is required.";
    } else {
        $email = sanitize_input($_POST["email"]);
        // Check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }

    // Sanitize Message
    if (empty($_POST["message"])) {
        $errors[] = "Message is required.";
    } else {
        $message = sanitize_input($_POST["message"]);
    }

    // If no errors, process the submission
    if (empty($errors)) {
        // Prepare the data to be saved
        $formatted_message = "Name: " . $name . "\n" .
                             "Email: " . $email . "\n" .
                             "Message: " . $message . "\n" .
                             "Submission Date: " . date("Y-m-d H:i:s") . "\n" .
                             "----------------------------------------\n\n";

        // Attempt to save to file
        // FILE_APPEND flag appends content to the file if it exists, or creates it if it doesn't.
        // LOCK_EX prevents anyone else from writing to the file at the same time.
        if (file_put_contents($filename, $formatted_message, FILE_APPEND | LOCK_EX) !== false) {
            $success_message = "Inputs sanitized and saved to www/messages.txt";
            // Clear the form fields after successful submission (optional)
            $_POST = array(); // Clears all POST data
        } else {
            $errors[] = "Error saving your message. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8; /* Light blue-gray background */
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md border border-gray-200">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Contact Us</h2>

        <?php
        // Display success message
        if (isset($success_message)) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">';
            echo '<strong class="font-bold">Success!</strong>';
            echo '<span class="block sm:inline"> ' . $success_message . '</span>';
            echo '</div>';
        }

        // Display error messages
        if (!empty($errors)) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert">';
            echo '<strong class="font-bold">Error!</strong>';
            echo '<ul class="mt-2 list-disc list-inside">';
            foreach ($errors as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" id="name" name="name"
                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       required>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea id="message" name="message" rows="5"
                          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                          required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
            </div>

            <div class="flex justify-center">
                <button type="submit"
                        class="w-full px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Submit Message
                </button>
            </div>
        </form>
    </div>
</body>
</html>
