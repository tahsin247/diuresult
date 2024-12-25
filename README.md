________________________________________
DIU Result Portal Documentation
________________________________________
Table of Contents
1.	Introduction 
o	Overview of the DIU Result Portal
o	Purpose and Objectives
o	Target Audience
2.	System Overview 
o	Features of the Portal
o	Technology Stack Used
3.	System Requirements 
o	Hardware Specifications
o	Software Prerequisites
o	Network Requirements
4.	System Design 
o	Architecture Overview
o	Database Design (Tables, Relations)
o	API Design
5.	Codebase Overview 
o	Explanation of Key Files and Folders
o	Code Functionality Breakdown
6.	Installation Guide 
o	Prerequisites
o	Installation Steps
7.	User Manual 
o	Viewing Results
o	Downloading Results
8.	Security Features 
o	Data Encryption
o	Access Control
o	Secure Communication
9.	FAQs 
o	Common Issues and Resolutions
10.	Conclusion 
o	Future Scope and Enhancements
o	Acknowledgments
________________________________________
1. Introduction
Overview of the DIU Result Portal
The DIU Result Portal is an integrated, web-based system that facilitates result management for students, faculty, and administrators of Daffodil International University. It simplifies result publication and retrieval, ensuring accuracy, security, and accessibility.
Features:
•	Automated result processing.
•	Secure, role-based access for different user types.
•	Downloadable and printable result formats.
________________________________________
Purpose and Objectives
•	Purpose: To streamline the process of academic result management and make result access faster and more secure.
•	Objectives: 
o	Reduce manual errors in managing academic records.
o	Provide a robust platform for data accessibility and transparency.
o	Empower administrators to oversee the system effectively.
________________________________________
Target Audience
1.	Students: Retrieve semester results and download them.
________________________________________













2. System Overview
Features of the Portal
1.	Student Result: 
o	View personal semester results.
o	Download results in PDF format.
________________________________________
Technology Stack Used
1.	Frontend: HTML, CSS, and JavaScript for a responsive interface.
2.	Backend: PHP for server-side logic and API handling.
3.	Hosting: Localhost for development; deployable to Vercel or any PHP-supported platform.
________________________________________
3. System Requirements
Hardware Specifications
For the Server:
•	Minimum: 
o	2-core CPU, 8GB RAM, 100GB SSD Storage.
o	4-core CPU, 8GB RAM, 100GB SSD Storage.
•	Recommended: 
o	4-core CPU, 16GB RAM, 200GB SSD Storage.
For Client Devices:
•	Any modern PC, smartphone, or tablet with an updated browser.
________________________________________
Software Prerequisites
•	PHP version 7.4 or higher.
•	MySQL version 5.7 or higher.
•	Apache or Nginx web server.
________________________________________



4. System Design
Architecture Overview
The portal employs a 2-tier architecture:
1.	Presentation Layer: Frontend interface for interaction.
2.	Application Layer: Backend server for logic and data handling.
________________________________________
Api Database Design
Key Tables
•	Students: student_id, name,  program.
•	Results: result_id, student_id, semester_id, course_id, grade, credit.
•	Semesters: semester_id, name, year.
Relationships:
1.	One-to-Many: A student can have multiple results.
2.	Many-to-One: Results belong to a specific semester.
________________________________________
5. Codebase Overview
Key Files
1.	diuapi.php: 
o	Handles API requests for retrieving student and result data.
2.	index.php: 
o	Displays student results and computes SGPA.
3.	vercel.json: 
o	Configuration file for deployment on Vercel.
________________________________________
Code Functionality
1.	SGPA Calculation: 
o	Computes SGPA dynamically based on course grades and credits.
2.	PDF Export: 
o	Allows students to download printable results.

________________________________________
6. Installation Guide
Prerequisites
•	Install PHP, MySQL, and Apache/Nginx server.
________________________________________
Installation Steps
1.	Clone the repository: 
2.	git clone https://github.com/tahsin247/diuresult.git
3.	cd diuresult
4.	Start the server: 
5.	php -S localhost:8000
________________________________________
7. User Manual
Logging In
1.	Open the URL.
2.	Enter your ID and the sleeted semester option.
3.	Click Submit.
________________________________________
Viewing Results
1.	Navigate to the Results page.
2.	Select your semester from the dropdown.
3.	Click Submit.
________________________________________
Downloading Results
•	Click the Download PDF button to save your results locally.
________________________________________






8. Security Features
Encryption
•	Passwords are hashed using bcrypt.
•	Data transmitted over HTTPS.
________________________________________
Role-Based Access Control
•	Admin, Faculty, and Students have clearly defined access privileges.
________________________________________
9. FAQs
1.	How do I export results? 
o	Use the "Print" button on the results page.
________________________________________
10. Conclusion
Future Scope
•	Mobile application integration.
•	Real-time notifications for result updates.

Acknowledgments
DEVELOPED BY THE SWE 41 SOFT MAFIA.
________________________________________
Email: tahsinhamim41@gmail.com
