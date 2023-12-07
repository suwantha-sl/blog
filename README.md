Prerequisite:
PHP 8.1 or higher (extensions such as openssl, pdo_mysqli etc... in php.ini file should be enabled)
MySQL 5.7 
Laravel 10.0.*
Node Js
This guide will support setting up the project in a Windows OS environment.

1. Download the project folder from the below GitHub link.
(https://github.com/suwantha-sl/blog.git)

2. Extract a copy of the downloaded folder to your local hard drive (Eg, C or D drive). 
In my example it is D:\vhost\blog-app.

3. Create a database with the name 'blogapp_db' using any DBMS application like MySQL workbench, phpmy admin, etc... You may use collation as 'utf8_unicode_ci' for this database.

4. Go to the project folder and open the .env file which is in the root folder. 
In .env file replace the database username and password with your database username and password in the following lines.
DB_USERNAME=your database username
DB_PASSWORD=your database password

5. The provided mail SMTP configurations with gmail, in the .env file are, correct and working fine. But if you wish to use your own mail server you may replace the parameters of the below lines in the .env file with your mail server details.
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
6. Setting up the database tables.
	There are 2 options to set up the database tables.
option 1 (using the blogapp-db.sql  file): 
	Look for the blogapp-db.sql file in the root directory of the extracted project folder in your local hard drive.
	Log into the 'blogapp_db' database which you created in step 3 with any DBMS application like MySQL workbench, PHP myadmin and execute queries in the blogapp-db.sql file or directly import the blogapp-db.sql file to set up the tables and stored procedures.	
option 2 (using php artisan migration commands):
	Open the command prompt and go to the project directory. In my example it is D:\vhost\blog-app
	Then type php artisan migrate and press enter. After executing this command the necessary tables will be created if you have correctly configured your database in steps 3 and 4.
	Then type php artisan db:seed --class=UserSeeder and press enter. This will create sample 3 users in users table. 
	Look for the sp_GetBlogComments.sql and sp_GetIndividualComments.sql' file in the root directory of the extracted project folder in your local hard drive. 
	Log into the 'blogapp_db ' database with any DBMS application and execute the above mentioned stored procedure sql files in order to create the stored procedure. 
7. After completing step 6 successfully you should have below mentioned tables and stored procedure 
in your 'blogapp_db' database.
	list of table names:
		blog_posts
		comments
		failed_jobs
		jobs
		migrations
		password_reset_tokens
		personal_access_tokens
		users

	stored procedure name:
		sp_GetBlogComments
		sp_GetIndividualComments

** 'sp_GetBlogComments' stored procedure is used to retrieve all published comments related to a particular blog post. Blog id is the input parameter for this stored procedure.
** ‘sp_GetIndividualComments’ stored procedure is used to retrieve comments related to own blogs if its Blog Admin. Super Admins will see all comments. User id and User Type are the input parameters.

8.  Executing the application
	Open the command prompt (cmd) and go to the project directory. In my example it is D:\vhost\blog-app
	Type php artisan storage:link and press enter. (This is important to link storage folder to public folder and used in image uploading and displaying related to blogs.)
	Type php artisan serve and press enter.
	Open another command prompt (terminal) and go to the project directory and type npm run dev and press enter.
	Open another command prompt (terminal) and go to the project directory and type php artisan queue:work and press enter.

** This project uses Laravel's Job Queue to send emails so that it improves the performance of the application. All the qued jobs will be saved to 'jobs' table in the 'blogapp_db' database. Execution of artisan queue:work command will process the job queue.

9. Finally open your web browser and type ' http://127.0.0.1:8000 ' in your web browser. You will see the login page as shown below.
	Please use below login credentials.
	Super Admin Account:
		username: test@test.com
		password: password

	Blog Admin Account:
		username: ishansg@yahoo.com.sg
		password: password

Blog Admin Account:
		username: suwantha.ig@gmail.com
		password: password
