<?php

// Define the name of the CSV file
$csv_file = 'data.csv';

// --- Create a dummy data.csv file for demonstration if it doesn't exist ---
// In a real scenario, this file would already exist.
if (!file_exists($csv_file)) {
    $dummy_content = <<<CSV
ID,Status,Name,Date
1,Active,John Doe,2023-01-15
2,Inactive,Jane Smith,2023-01-20
3,Pending,Peter Jones,2023-02-01
4,Active,Alice Brown,2023-02-10
5,Cancelled,Bob White,2023-03-05
6,active,Charlie Green,2023-03-10
7,Active,Diana Prince,2023-04-01
CSV;
    file_put_contents($csv_file, $dummy_content);
}
// --- End of dummy file creation ---

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Reader</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8; /* Light blue-gray background */
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #e2e8f0; /* Tailwind gray-200 */
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8fafc; /* Tailwind gray-50 */
            font-weight: 600;
            color: #4a5568; /* Tailwind gray-700 */
        }
        tr:nth-child(even) {
            background-color: #f0f4f8; /* Light blue-gray for alternating rows */
        }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">CSV Data (Status: Active)</h1>

        <?php
        // Attempt to open the CSV file in read mode ('r')
        if (($handle = fopen($csv_file, 'r')) !== FALSE) {
            // Skip the header row
            fgetcsv($handle); // Reads the first line and discards it

            echo '<div class="overflow-x-auto">'; // Add responsiveness for tables
            echo '<table>';
            echo '<thead><tr><th>ID</th><th>Status</th><th>Name</th><th>Date</th></tr></thead>';
            echo '<tbody>';

            $row_count = 0;
            // Loop through each remaining row in the CSV file
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Check if the row has at least 2 columns (index 0 and 1)
                if (isset($data[1])) {
                    // Check if the second column (index 1) contains the word "Active" (case-insensitive)
                    // using stripos() which returns the position of the first occurrence of substring, or FALSE if not found.
                    if (stripos($data[1], "Active") !== FALSE) {
                        $row_count++;
                        echo '<tr>';
                        // Output each column for the matched row
                        foreach ($data as $col) {
                            echo '<td>' . htmlspecialchars($col) . '</td>';
                        }
                        echo '</tr>';
                    }
                }
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>'; // Close overflow-x-auto

            if ($row_count === 0) {
                echo '<p class="text-center text-gray-600 mt-4">No rows found with "Active" status.</p>';
            }

            // Close the file handle
            fclose($handle);
        } else {
            // Display an error message if the file cannot be opened
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative" role="alert">';
            echo '<strong class="font-bold">Error!</strong>';
            echo '<span class="block sm:inline"> Could not open the CSV file: ' . htmlspecialchars($csv_file) . '. Please ensure it exists and is readable.</span>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
