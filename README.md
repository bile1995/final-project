# Smart Waste Sorting & Collection System

A web-based system that helps apartment communities sort, track, and collect waste more efficiently. Built as my university senior project.

## The problem
Collections follow fixed schedules, so bins overflow or are emptied half-full. Waste is rarely sorted at the source, and disposal logs are manual.

## What it does
- Residents log waste type and quantity, view their history, and get notifications.
- Admins manage users and bins, assign pickups to collectors, and generate reports.
- Collectors see assigned pickups and record completed collections.
- Bin fill levels are estimated from logged waste, with no hardware sensors. Notifications fire when a bin nears capacity.
- Reports cover waste volume, bin utilization, and collector performance, with export to Word/Excel.
- Role-based access with session authentication, so each role only sees its own pages.

## Tech stack
PHP, MySQL, JavaScript, HTML, CSS, Bootstrap. Developed on XAMPP.

## Project structure
- html/ : frontend pages and forms
- php/ : backend logic, dashboards, sessions, reports

## Run locally
1. Install XAMPP and start Apache and MySQL.
2. Copy the project into htdocs/.
3. Create a MySQL database and import the SQL file.
4. Edit the credentials in php/db.php.
5. Open http://localhost/final_project/html/home.html.

## Screenshots
Log-in page
<img width="975" height="548" alt="image" src="https://github.com/user-attachments/assets/51df3c02-0c79-4d42-bb1c-f650c77e0053" />
Admin dashboard
<img width="975" height="548" alt="image" src="https://github.com/user-attachments/assets/63b89dcd-1d62-4117-bb54-bb7c6e11290d" />
Resident dashboard
<img width="917" height="516" alt="image" src="https://github.com/user-attachments/assets/65ace5d1-c05c-4afc-b15a-1d21a5086af8" />
Collecter dashboard
<img width="992" height="558" alt="image" src="https://github.com/user-attachments/assets/7faec581-cbf5-45fd-addf-4aa580306f55" />


## Future work
Sensor-based smart bins, AI waste-type prediction, and geolocation-based route planning.
