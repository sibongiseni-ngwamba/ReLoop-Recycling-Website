ReLoop Recycling Website - Setup Instructions

1. Copy the reloop folder into your WampServer web root:
   C:\wamp64\www\reloop
   or, on older installations:
   C:\wamp\www\reloop

2. Start WampServer and make sure Apache and MySQL are running.

3. Open phpMyAdmin:
   http://localhost/phpmyadmin/

4. Import the database:
   - Click Import.
   - Choose database/reloop_database.sql from this project.
   - Click Go.
   The script creates the reloop_db database, all required tables, and sample data.

5. Open the website:
   http://localhost/reloop/

6. Sample login details:
   Admin:
   email: admin@reloop.co.za
   password: Admin@123

   User:
   email: user@reloop.co.za
   password: User@123

7. Database connection settings are in:
   includes/db.php
   Default WampServer settings are used:
   host: localhost
   database: reloop_db
   username: root
   password: empty

Note:
The SQL seed stores sample accounts with NEEDS_HASH markers. On first successful login,
login.php immediately replaces the marker with a secure password_hash() value and then
authenticates with password_verify().
