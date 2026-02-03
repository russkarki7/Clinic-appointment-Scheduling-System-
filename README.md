# Clinic Appointment Scheduling System

## Live Website
https://student.heraldcollege.edu.np/~np03cs4s240191/Russkarki_FinalAssignment/

## Setup Instructions
1. Upload project folder to server public_html
2. Import clinic_db.sql into MySQL
3. Update config/db.php with database credentials
4. Access the website via browser

## Database Configuration
Database Name: np03cs4s240191  
Username: np03cs4s240191  
Password: VCbZA5VhUN
## Features Implemented
- CRUD operations for Patients
- CRUD operations for Doctors
- CRUD operations for Appointments
- Prevent overlapping doctor appointments
- Search appointments by date, doctor, patient
- Clean UI with responsive layout

## Overlapping Logic
Appointments are checked using start_time and end_time to prevent conflicts.

## Known Issues
- No user authentication (admin only access)
- No email notifications

## Technologies Used
- PHP (Procedural)
- MySQL
- HTML5
- CSS3
- JavaScript (AJAX for availability check)

## Author
Name: <Russ Karki>  
Student ID:2436484
