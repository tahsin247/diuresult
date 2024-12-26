<?php

// Map semester IDs to names
$semesterMap = [
    "243" => "Fall 2024",
    "241" => "Spring 2024",
    "233" => "Fall 2023",
];

// Initialize variables
$studentName = $programName = $facultyName = $batchNo = 'Unknown';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = htmlspecialchars(trim($_POST['studentId']));
    $selectedSemesters = $_POST['semesters'] ?? [];

    // Validate input fields
    if (empty($studentId) || empty($selectedSemesters)) {
        $error = "Please provide your Student ID and select at least one semester.";
    } else {
        $allResults = [];
        $totalCredits = 0;
        $totalGradePoints = 0;

        foreach ($selectedSemesters as $semesterId) {
            $semesterName = $semesterMap[$semesterId] ?? 'Unknown Semester';
            $apiUrl = "http://localhost/diuapi.php/?studentId=$studentId&semesterId=$semesterId";

            // Fetch data from the API
            $response = file_get_contents($apiUrl);
            if ($response === false) {
                $error = "Failed to connect to the API for Semester $semesterName.";
                break;
            } else {
                $data = json_decode($response, true);
                if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                    $error = "Error decoding API response for Semester $semesterName: " . json_last_error_msg();
                    break;
                } else {
                    $semesterResults = $data['semesterResult'] ?? [];
                    $studentName = $data['studentInfo']['studentName'] ?? 'Unknown';
                    $programName = $data['studentInfo']['programName'] ?? 'Unknown';
                    $facultyName = $data['studentInfo']['facultyName'] ?? 'Unknown';
                    $batchNo = $data['studentInfo']['batchNo'] ?? 'Unknown';

                    foreach ($semesterResults as &$result) {
                        $result['semesterName'] = $semesterName; // Add semester name to each course
                    }
                    $allResults = array_merge($allResults, $semesterResults);

                    // Calculate total credits and grade points
                    foreach ($semesterResults as $result) {
                        $credit = $result['totalCredit'];
                        $gradePoint = $result['pointEquivalent'];
                        $totalCredits += $credit;
                        $totalGradePoints += $credit * $gradePoint;
                    }
                }
            }
        }

        // Calculate CGPA
        $cumulativeCgpa = $totalCredits > 0 ? round($totalGradePoints / $totalCredits, 2) : 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIU Cumulative CGPA Calculator</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #00897B, #0081bf);
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 1.8rem;
            color: #333;
        }
        .form-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .form-section form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            width: 100%;
        }
        .form-section input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            width: 100%;
            max-width: 300px;
        }
        .form-section button {
            background: #2575fc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            max-width: 150px;
        }
        .form-section button:hover {
            background: #6a11cb;
        }
        .checkboxes {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .checkboxes label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 1rem;
        }
        .result-box {
            text-align: center;
            margin-top: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .result-box h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
        }
        .summary-table, .result-table {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border-collapse: collapse;
            text-align: left;
        }
        .summary-table th, .summary-table td, .result-table th, .result-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }
        .summary-table th, .result-table th {
            background: #6a11cb;
            color: white;
            text-align: left;
        }
        .summary-table .cgpa-row {
            text-align: center;
            font-weight: bold;
            color: red;
            background: #fff;
        }
        .print-button {
            background: #2575fc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 20px;
        }
        .print-button:hover {
            background: #6a11cb;
        }
        @media screen and (max-width: 768px) {
            .summary-table, .result-table {
                font-size: 0.9rem;
            }
            .form-section form {
                flex-direction: column;
                align-items: center;
            }
            .form-section input, .form-section button {
                max-width: 100%;
            }
        }
        @media print {
            body {
                background: white;
                color: black;
            }
            .form-section, .header h1 {
                display: none;
            }
            .container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                width: 100%;
            }
        }
    </style>
    <script>
        function printResults() {
            const printContents = document.querySelector('.result-box').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="https://daffodilvarsity.edu.bd/template/images/diulogoside.png" alt="DIU Logo">
        <h1>DIU Cumulative CGPA Calculator</h1>
    </div>

    <div class="form-section">
        <form method="POST">
            <input type="text" name="studentId" placeholder="Enter Student ID" required>
            <div class="checkboxes">
                <?php foreach ($semesterMap as $semesterId => $semesterName): ?>
                    <label>
                        <input type="checkbox" name="semesters[]" value="<?= $semesterId ?>">
                        <?= $semesterName ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <button type="submit">Calculate CGPA</button>
        </form>
    </div>

    <?php if (isset($error)): ?>
        <p class="error" style="text-align: center; color: red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if (isset($cumulativeCgpa)): ?>
        <div class="result-box">
            <h2>Student Academic Summary</h2>
            <table class="summary-table">
                <tr>
                    <th>Name</th>
                    <td><?= htmlspecialchars($studentName) ?></td>
                </tr>
                <tr>
                    <th>ID</th>
                    <td><?= htmlspecialchars($studentId) ?></td>
                </tr>
                <tr>
                    <th>Program</th>
                    <td><?= htmlspecialchars($programName) ?></td>
                </tr>
                <tr>
                    <th>Faculty</th>
                    <td><?= htmlspecialchars($facultyName) ?></td>
                </tr>
                <tr>
                    <th>Batch</th>
                    <td><?= htmlspecialchars($batchNo) ?></td>
                </tr>
                <tr>
                    <th>Total Completed Credits</th>
                    <td><?= $totalCredits ?></td>
                </tr>
                <tr>
                    <th colspan="2" class="cgpa-row">Total CGPA: <?= $cumulativeCgpa ?></th>
                </tr>
            </table>

            <h2>Course Results Summary</h2>
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Credit</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allResults as $result): ?>
                        <tr>
                            <td><?= htmlspecialchars($result['semesterName']) ?></td>
                            <td><?= htmlspecialchars($result['customCourseId']) ?></td>
                            <td><?= htmlspecialchars($result['courseTitle']) ?></td>
                            <td><?= htmlspecialchars($result['totalCredit']) ?></td>
                            <td><?= htmlspecialchars($result['gradeLetter']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="print-button" onclick="printResults()">Print Results</button>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
