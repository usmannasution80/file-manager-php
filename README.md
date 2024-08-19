# File Manager PHP
### Requirements :
- PHP >= 8.2
- MySQL (Optional)
### How to Run
- Open your command line or terminal
- Navigate to File Manager PHP directory
- Rename __.env.example__ to __.env__
- At __.env__ file, set `ROOT` value to path you want as root that will be accessed from this program.
- Run `php artisan serve`
### Delete, Rename, Upload
If you want to use delete, rename and upload functionality, you should have MySQL installed on your device.

If you already have MySQL installed, you should edit __.env__ file at the MySQL section correspond to your MySQL.

And then, you should run command `php artisan migrate` from your command line at __File Manager PHP__ directory.

### Create Admin
If you already setup your database based on above section, you can create admin that can do upload, delete, and rename files.

Navigate to your __File Manager PHP__ and run `php artisan create_admin username user@email password` (change username, user@email and password as you want).