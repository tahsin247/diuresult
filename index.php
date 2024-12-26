<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIU Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #004d40, #009688);
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        /* Header Section */
        .header {
            text-align: center;
            padding: 20px 20px;
            background: #ffffff;
            color: #00695c;
        }
        .header img {
            width: 120px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 2rem;
        }

        /* Menu Section */
        .menu {
            display: flex;
            justify-content: center;
            background: #00695c;
            padding: 10px;
        }
        .menu a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            margin: 0 15px;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .menu a:hover {
            background: #004d40;
            transform: translateY(-3px);
        }

        /* Content Section */
        .content {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            padding: 30px;
        }
        .box {
            flex: 1 1 calc(45% - 20px);
            max-width: 45%;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .box h2 {
            margin: 0 0 15px;
            color: #004d40;
            font-size: 1.5rem;
        }
        .box p {
            color: #555;
            font-size: 1rem;
            line-height: 1.6;
        }
        .box button {
            background: #2575fc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .box button:hover {
            background: #004d40;
            transform: scale(1.05);
        }

        /* User Manual Section */
        .user-manual {
            padding: 20px;
            background: #eeeeee;
            text-align: center;
        }
        .user-manual h2 {
            font-size: 1.5rem;
            color: #004d40;
            margin-bottom: 15px;
        }
        .user-manual p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 1rem;
            line-height: 1.8;
            color: #555;
        }

        /* Footer Section */
        .footer {
            background: #004d40;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .box {
                flex: 1 1 calc(100% - 20px);
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Section -->
    <div class="header">
        <img src="https://daffodilvarsity.edu.bd/template/images/diulogoside.png" alt="DIU Logo">
        <h1>Academic Result Publishing Portal</h1>
    </div>

    <!-- Menu Section -->
    <div class="menu">
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
    </div>

    <!-- Content Section -->
    <div class="content">
        <!-- Box 1 -->
        <div class="box">
            <h2>Find Semester Result</h2>
            <p>Access detailed results of your completed semesters with subject-wise grades.</p>
            <button onclick="location.href='semester_result.php';">Find Result</button>
        </div>

        <!-- Box 2 -->
        <div class="box">
            <h2>CGPA Calculator</h2>
            <p>Calculate your cumulative GPA easily by selecting completed semesters.</p>
            <button onclick="location.href='totalcgpa.php';">Calculate</button>
        </div>
    </div>

    <!-- User Manual Section -->
    <div class="user-manual">
        <h2>How to Use the Portal</h2>
        <p>
            This portal helps you find your semester results and calculate your CGPA across multiple semesters. 
            Navigate to the respective section using the buttons above for a seamless experience.
        </p>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        &copy; <?= date('Y') ?> Daffodil International University. Developed with by Montu.
    </div>
</div>

</body>
</html>
