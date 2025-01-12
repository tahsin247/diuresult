<?php

// API to fetch semester list
$semesterApiUrl = "http://diursultv2.onrender.com/apisocket/semesterListapi.php";
$semesterMap = [];

// Fetch semester list from API
try {
    $semesterResponse = file_get_contents($semesterApiUrl);
    if ($semesterResponse === false) {
        $error = "Failed to fetch semester list from API.";
    } else {
        $semesterMap = json_decode($semesterResponse, true);
        if ($semesterMap === null || json_last_error() !== JSON_ERROR_NONE) {
            $error = "Error decoding semester list from API: " . json_last_error_msg();
            $semesterMap = [];
        }
    }
} catch (Exception $e) {
    $error = "Error fetching semester list: " . $e->getMessage();
    $semesterMap = [];
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = htmlspecialchars(trim($_POST['studentId']));
    $semesterId = htmlspecialchars(trim($_POST['semesterId']));

    // Validate input fields
    if (empty($studentId) || empty($semesterId)) {
        $error = "Please provide both Student ID and Semester.";
    } else {
        // API URL for student results
        $apiUrl = "http://diursultv2.onrender.com/diuapi.php/?studentId=$studentId&semesterId=$semesterId";

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
    <title>Semester Results - DIU Academic Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
/* Base Styles */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to right, #004d40, #009688);
    color: #333;
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    width: calc(100% - 40px);
    box-sizing: border-box;
}

/* Header Styles */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #ffffff;
    border-bottom: 2px solid #ddd;
    flex-wrap: wrap;
    gap: 15px;
}

.header img {
    width: 100px;
    height: auto;
    object-fit: contain;
}

.header h1 {
    font-size: 1.5rem;
    color: #333;
    margin: 0;
    text-align: center;
    flex: 1;
}


/* Form Styles */
.form-section {
    padding: 20px;
    margin-bottom: 20px;
}

.form-section form {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
    max-width: 800px;
    margin: 0 auto;
}

.form-section input,
.form-section select {
    padding: 12px;
    font-size: 1rem;
    border-radius: 8px;
    border: 1px solid #ddd;
    flex: 1;
    min-width: 200px;
    max-width: 300px;
    background: #fff;
}

.form-section button {
    background: #2575fc;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
    min-width: 120px;
}

.form-section button:hover {
    background: #6a11cb;
    transform: translateY(-2px);
}

/* Result Section Styles */
.result-section {
    padding: 20px;
    overflow-x: auto;
}

.result-section h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.result-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.result-table th,
.result-table td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
}

.result-table th {
    background: #6a11cb;
    color: white;
    font-weight: 500;
}

.result-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.sgpa-section {
    text-align: center;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 20px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

/* Print Button Styles */
.print-button {
    display: block;
    margin: 20px auto;
    background: #2575fc;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.print-button:hover {
    background: #6a11cb;
    transform: translateY(-2px);
}

/* Footer Styles */
.footer {
    text-align: center;
    padding: 15px;
    background: #004d40;
    color: white;
    font-size: 0.9rem;
    border-radius: 0 0 12px 12px;
    margin-top: auto;
}

/* Print Styles */
@media print {
    body {
        background: white;
        color: black;
    }

    .header, .menu, .form-section, .footer, .print-button {
        display: none;
    }

    .container {
        box-shadow: none;
        margin: 0;
        padding: 0;
        border-radius: 0;
        width: 100%;
    }

    .result-section {
        margin: 0;
        padding: 10px;
    }

    .result-table {
        box-shadow: none;
    }
}

/* Responsive Styles */
@media (max-width: 992px) {
    .container {
        margin: 10px auto;
    }

    .header {
        padding: 15px;
    }

    .header h1 {
        font-size: 1.3rem;
    }
}

@media (max-width: 768px) {
    .header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .header img {
        width: 80px;
    }

    .header h1 {
        font-size: 1.2rem;
        order: 2;
    }

    .menu {
        padding: 10px;
        flex-direction: column;
        align-items: stretch;
    }

    .menu a {
        text-align: center;
        margin: 5px 0;
    }

    .form-section form {
        flex-direction: column;
        align-items: stretch;
    }

    .form-section input,
    .form-section select,
    .form-section button {
        max-width: 100%;
        width: 100%;
    }

    .result-table {
        font-size: 0.9rem;
    }

    .print-button {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    .container {
        margin: 5px;
        padding: 10px;
    }

    .header img {
        width: 60px;
    }

    .header h1 {
        font-size: 1.1rem;
    }

    .menu a {
        font-size: 0.9rem;
        padding: 8px 15px;
    }

    .form-section {
        padding: 10px;
    }

    .form-section input,
    .form-section select {
        font-size: 0.9rem;
        padding: 10px;
    }

    .result-table th,
    .result-table td {
        padding: 8px;
        font-size: 0.8rem;
    }

    .sgpa-section {
        font-size: 1rem;
    }

    .footer {
        font-size: 0.8rem;
        padding: 10px;
    }
}

/* Accessibility Improvements */
@media (prefers-reduced-motion: reduce) {
    * {
        transition: none !important;
        animation: none !important;
    }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
    .menu {
        background: #000;
    }

    .menu a:hover {
        background: #333;
    }

    .result-table th {
        background: #000;
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
        <!-- Header -->
        <div class="header">
            <img src="https://daffodilvarsity.edu.bd/template/images/diulogoside.png" alt="DIU Logo">
            <h1>DIU Semester Result!</h1>
          
        </div>

        <!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Dropdown Menu Styles */
        .menu {
            position: relative;
            display: flex;
            justify-content: center;
            background: #00695c;
            padding: 0;
            flex-wrap: wrap;
        }

        .menu-item {
            position: relative;
            display: inline-block;
        }

        .menu-link {
            display: block;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            padding: 15px 20px;
            transition: background-color 0.3s;
        }

        .menu-link:hover {
            background: #004d40;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #00695c;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 0 0 4px 4px;
        }

        .menu-item:hover .dropdown {
            display: block;
        }

        .dropdown-link {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .dropdown-link:hover {
            background: #004d40;
        }

        /* Mobile Menu Styles */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 10px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .menu {
                flex-direction: column;
                align-items: stretch;
                padding: 0;
            }

            .menu-toggle {
                display: block;
                width: 100%;
                background: #00695c;
                text-align: right;
                padding: 15px;
            }

            .menu-items {
                display: none;
                width: 100%;
            }

            .menu-items.active {
                display: block;
            }

            .menu-item {
                display: block;
                width: 100%;
            }

            .menu-link {
                padding: 15px;
                border-top: 1px solid rgba(255,255,255,0.1);
            }

            .dropdown {
                position: static;
                background: #005448;
                box-shadow: none;
                width: 100%;
            }

            .dropdown-link {
                padding-left: 40px;
            }
        }
    </style>
</head>
<body>
    <!-- Replace your existing menu with this structure -->
    <div class="menu">
        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        <div class="menu-items">
            <div class="menu-item">
                <a href="index.php" class="menu-link">Home</a>
            </div>
            <div class="menu-item">
                <a href="semester_result.php" class="dropdown-link">Results</a>
                </div>
                    <div class="menu-item">
                <a href="totalcgpa.php" class="dropdown-link">CGPA Calculator</a>
                        </div>
            <div class="menu-item">
                <a href="about.php" class="menu-link">About</a>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const menuItems = document.querySelector('.menu-items');
            menuItems.classList.toggle('active');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.querySelector('.menu');
            const menuItems = document.querySelector('.menu-items');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (!menu.contains(event.target) && menuItems.classList.contains('active')) {
                menuItems.classList.remove('active');
            }
        });

        // Handle touch events for mobile
        document.addEventListener('touchstart', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target)) {
                    dropdown.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>


        <!-- Form Section -->
        <div class="form-section">
            <form method="POST">
                <input type="text" name="studentId" placeholder="Enter Student ID" required>
                <select name="semesterId" required>
                    <option value="">Select Semester</option>
                    <?php foreach ($semesterMap as $semester): ?>
                        <option value="<?= htmlspecialchars($semester['semesterId']) ?>">
                            <?= htmlspecialchars($semester['semesterName'] . " " . $semester['semesterYear']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Get Results</button>
            </form>
        </div>

        <!-- Results -->
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?= $error ?></p>
        <?php endif; ?>

        <?php if (isset($studentInfo) && !empty($studentInfo)): ?>
            <div class="result-section">
                <h2>Student Information</h2>
                <table class="result-table">
                    <tr><th>Student ID</th><td><?= htmlspecialchars($studentInfo['studentId']) ?></td></tr>
                    <tr><th>Name</th><td><?= htmlspecialchars($studentInfo['studentName']) ?></td></tr>
                    <tr><th>Program</th><td><?= htmlspecialchars($studentInfo['programName']) ?></td></tr>
                    <tr><th>Faculty</th><td><?= htmlspecialchars($studentInfo['facultyName']) ?></td></tr>
                    <tr><th>Batch</th><td><?= htmlspecialchars($studentInfo['batchNo']) ?></td></tr>
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
                <div class="sgpa-section">SGPA: <?= $sgpa ?></div>
            </div>
            <button class="print-button" onclick="printResults()">Print Results</button>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?= date('Y') ?> Daffodil International University. Developed by Tahsin Hamim. All rights reserved.
    </div>
</body>
</html>
