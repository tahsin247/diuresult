<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = htmlspecialchars(trim($_POST['studentId']));
    $semesterId = htmlspecialchars(trim($_POST['semesterId']));

    // Validate input fields
    if (empty($studentId) || empty($semesterId)) {
        $error = "Please provide both Student ID and Semester.";
    } else {
        // API URL
        $apiUrl = "http://localhost/diuapi.php/?studentId=$studentId&semesterId=$semesterId";

        // Fetch data from the API
        $response = file_get_contents($apiUrl);
        if ($response === false) {
            $error = "Failed to connect to the API. Please check the server.";
        } else {
            // Decode the JSON response
            $data = json_decode($response, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $error = "Error decoding API response: " . json_last_error_msg();
            } else {
                $studentInfo = $data['studentInfo'] ?? [];
                $semesterResults = $data['semesterResult'] ?? [];

                // Calculate SGPA
                $totalCredits = 0;
                $totalGradePoints = 0;

                foreach ($semesterResults as $result) {
                    $credit = $result['totalCredit'];
                    $gradePoint = $result['pointEquivalent'];
                    $totalCredits += $credit;
                    $totalGradePoints += $credit * $gradePoint;
                }

                $sgpa = $totalCredits > 0 ? round($totalGradePoints / $totalCredits, 2) : 0;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIU Semester Result Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #00897B, #0081bf);
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            width: 100px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 2.2rem;
            color: #333;
        }
        .form-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }
        .form-section form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
            max-width: 600px;
        }
        .form-section input, .form-section select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            width: 250px;
            max-width: 100%;
        }
        .form-section button {
            background: #2575fc;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .form-section button:hover {
            background: #6a11cb;
        }
        .error {
            text-align: center;
            color: red;
            margin-bottom: 20px;
        }
        .result-section h2 {
            text-align: center;
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 15px;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        .result-table th, .result-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .result-table th {
            background: #6a11cb;
            color: white;
        }
        .result-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .sgpa-section {
            text-align: center;
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        .print-button {
            display: block;
            margin: 20px auto;
            background: #2575fc;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .print-button:hover {
            background: #6a11cb;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                color: black;
            }
            .container {
                border: none;
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                page-break-inside: avoid;
            }
            .header img {
                width: 100px;
                margin-bottom: 20px;
            }
            .print-button, .form-section {
                display: none;
            }
            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>
    <script>
        function printResults() {
            window.print();
        }
    </script>
</head>
<body>

    <div class="container">
        <div class="header">
            <!-- DIU Logo -->
            <img src="https://daffodilvarsity.edu.bd/template/images/diulogoside.png" alt="DIU Logo">
            <h1>DIU Semester Result Portal</h1>
        </div>

        <div class="form-section">
            <form method="POST">
                <input type="text" name="studentId" placeholder="Enter Student ID" required>
                <select name="semesterId" required>
                    <option value="">Select Semester</option>
                    <option value="243">Fall 2024</option>
                    <option value="241">Spring 2024</option>
                    <option value="233">Fall 2023</option>
                </select>
                <button type="submit">Get Result</button>
            </form>
        </div>

        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <?php if (isset($studentInfo) && !empty($studentInfo)): ?>
            <div class="result-section">
                <h2>Student Information</h2>
                <table class="result-table">
                    <tr><th>Student ID</th><td><?= htmlspecialchars($studentInfo['studentId']) ?></td></tr>
                    <tr><th>Name</th><td><?= htmlspecialchars($studentInfo['studentName']) ?></td></tr>
                    <tr><th>Program</th><td><?= htmlspecialchars($studentInfo['programName']) ?></td></tr>
                    <tr><th>Department</th><td><?= htmlspecialchars($studentInfo['departmentName']) ?></td></tr>
                    <tr><th>Batch</th><td><?= htmlspecialchars($studentInfo['batchNo']) ?></td></tr>
                    <tr><th>Faculty</th><td><?= htmlspecialchars($studentInfo['facultyName']) ?></td></tr>
                    <tr><th>Semester</th><td><?= htmlspecialchars($studentInfo['semesterName']) ?></td></tr>
                </table>
            </div>
        <?php endif; ?>

        <?php if (isset($semesterResults) && !empty($semesterResults)): ?>
            <div class="result-section">
                <h2>Semester Results</h2>
                <table class="result-table">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Credit</th>
                            <th>Grade</th>
                            <th>Grade Point</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($semesterResults as $result): ?>
                            <tr>
                                <td><?= htmlspecialchars($result['customCourseId']) ?></td>
                                <td><?= htmlspecialchars($result['courseTitle']) ?></td>
                                <td><?= htmlspecialchars($result['totalCredit']) ?></td>
                                <td><?= htmlspecialchars($result['gradeLetter']) ?></td>
                                <td><?= htmlspecialchars($result['pointEquivalent']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="sgpa-section">SGPA: <?= isset($sgpa) ? $sgpa : 'N/A' ?></div>
            </div>
            <button class="print-button" onclick="printResults()">Print Results</button>
        <?php endif; ?>
    </div>

</body>
</html>
