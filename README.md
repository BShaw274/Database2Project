# Database2Project
The goal for the project is to create a web based application with a database that tracks and stores students meeting and studying together. 


Team Members: Connor Gonthier, Michael Whalen, Brian Shaw, and Tzur Almog
To install:
Go to DatabaseInitaliser.html
Choose test for some sample users
Choose empty for a new database

To run and 
Start by opening Homepage.html

Explanation for files is as follows:
-Homepage.html
Here there is a form where a user can input their Email and Password and click the Login button to login and be redirected to the correct page.
Or a New User can Click the Student Registration or Parent Registration buttons
To be linked to either of the two registration pages

	-UserSignupStudent.html 	This is the student registration page
Here the user can register as a student by filling in the form completely including the parent ID of their parents account which must be registered before a student can register.
The information submitted is the Student’s : Email, Password, Name, Phone Number, Grade Level and Parent ID. 
This page then calls the userSignupStudent.php and passes it the information inputted, so it can run the check and create a new account in the database.

	-UserSignupStudent.php
This takes the information posted by the UserSignupStudent.html form and then checks that the information is valid and if it is then creates a new student that is a child of the parent ID provided. This will create new entries both in the users table and students table.

	-UserSignupParent.html 		This is the Parent registration page
Here the user can register as a parent by entering information to create a new account.
The information required is: Email, Password, Name and Phone Number
It then submits this information to the UserSignupParent.php to create an account.

	-UserSignupParent.php
This takes the information posted by the UserSignupParent.html form and creates a new parent user. This creates new entries in the Users table and Parents table.

-UserLogin.php
This takes information posted from Homepage.html and checks that it is a valid user and then redirects the user to the appropriate logged in page, depending on if they are an Admin, Parent, or student. 

	-ParentLogin.php
This is the Login page for parents. First a parent can update their own information with the first form, which will link the inputted information to selfUpdate.php. Then the webpage displays the account information for all students who are linked to your account so that the parent may update the students information, submitting the new information to update will pass the information to parentUpdateStudents.php to update the student’s account. Next it displays a section that allows the parent to enroll their students as a mentor and/or mentee which will pass the information to the ParentAssignMentor.php and ParentAssignMentee.php respectively. After that section the page displays all current meetings below so the parents can use that information to enroll their students into meetings
	-SelfUpdate.php
This page is used by both ParentLogin.php and StudentLogin.php in being able to update their own accounts. This takes any inputted information and updates the information in the users table.

	-parentUpdateStudent.php
This file is a modified version of SelfUpdate.php so that the parent account logged in updates their students information that they entered. It also checks that the student ID you enter is the parents student.

	-ParentAssignMentor.php
This page is passed the student ID and the meeting ID, and checks if the student can join that meeting as a mentor. This file checks if the student ID is a student of the parent logged in, then if they can join the meeting, checking for time slot conflicts, grade level conflicts, and capacity conflicts. This will update tables mentors if the student becoming a mentor was not previously a mentor, it will update the enroll2 table with the new mentor enrollment.

	-ParentAssignMentee.php
This page does the same thing as ParentAssignMentor.php but for mentees This will update tables mentees if the student becoming a mentee was not previously a mentee, it will update the enroll table with the new mentee enrollment and meeting id.

	-StudentLogin.php
This is the login page for students, First it shows the information of the student that is logged in so they can fill out the following to update their account information if they wish. This form passes the information to SelfUpdate.php which was talked about in ParentLogin.php but it allows the student to update their account information. Next we have the ability to sign up as a mentor or mentee for meetings as well as quitting a meeting as a mentor or mentee. The information here is linked to StudentAssignMentor.php, StudentAssignMentee.php, StudentRemoveMentor.php and StudentRemoveMentee.php. These files allow a student to sign themselves up as Mentor/Mentee and remove themself from a meeting as mentor/mentee. Below that it shows the participants of any meetings the Student is a mentor for. Then Study materials are displayed for any meetings they are a part of. Then it displays all meeting information to be used in meeting signup/signout.
-StudentAssignMentor.php 
This is similar to ParentAssignsMentor.php. IT allows a student to sign themselves up as a mentor for a meeting so long as the proper checks pass. Those being Grade level, timeslot, and capacity checks. This will update the Enroll2 table with the new mentor and meet_id, and will update the mentor table if the student wasn’t previously a mentor.

-StudentAssignMentee.php
This is similar to ParentAssignsMentee.php. It allows a student to sign themselves up as a mentor for a meeting so long as the proper checks pass. Those being Grade level, timeslot, and capacity checks. This will update the Enroll table with the new mentee and meet_id, and will update the mentee table if the student wasn’t previously a mente.

-StudentRemoveMentor.php
This page is passed the student ID and the meeting ID, and checks if the student is in the meeting and can leave the meeting. The file checks whether the meeting is too late to be exited and whether the request is to leave one or all future meetings. The file then removes the student from the enroll2 table accordingly. If this causes the student to no longer be a mentor then they are removed from the mentor table as well.

-StudentRemoveMentee.php
This page is passed the student ID and the meeting ID, and checks if the student is in the meeting and can leave the meeting. The file checks whether the request is to leave one or all future meetings. The file then removes the student from the enroll table accordingly. If this causes the student to no longer be a mentee then they are removed from the mentee table as well.

	-AdminSignedIn.php
This page is where the only admin is redirected after signing in. The admin credentials are email: admin@gmail.com pass: admin. This is the only admin user and once signed in this page displays all the admin can do. The first section is creating a new meeting. Here the admin has the ability to fully create a meeting with the name, date, capacity, announcement, timeslot, group, and whether or not to make the meeting a singular event or recurring event. This field redirects to AdminCreateMeeting.php. The next section details adding a mentor or mentee to a meeting. These two fields will redirect to AdminAssignMentee.php and AdminAssignMentor.php. The next and final functionality that is specific to admins is the “Add Study Materials to a Meeting.” This will redirect to AdminAssignStudyMaterials.php. The final section of the web page displays all of the meetings like the other pages do.

	-AdminCreateMeeting.php
This page is where the AdminSignedIn.php redirects to. This file will take the input and create a meeting off of the data it receives. The only real logic here is seeing if a meeting is recurring or singular, since a recurring meeting will duplicate itself at least 10 times into the database with the correct dates attached. 

	-AdminAssignMentor.php and AdminAssignMentee.php
These two php files are pretty similar in how they work with some minor tweaks. They both work by taking in the student Id, meeting Id, and if they are to be assigned for all recurring meetings or a singular meeting. The major checks being done are to make sure the student is the proper grade level, that they have no prior time conflicts, and that the meeting has capacity in the specified role for the meeting. There are also checks on if a student already has the role of mentor or mentee to prevent duplicate values from being attempted to be created. 

	-AdminAssignStudyMaterials
This php file is only for adding study materials to a specific meeting. It only requires the materials Id and the meeting Id and does not have any constraints to deal with.

	-ThursdayRun
This php file is made to be run on midnight Thursdays. It gets a list of all the meetings that still need mentees. Then it gets a list of all students that need to be notified about meetings that are closing due to lack of mentees. After deletes all meetings that don't have enough mentees and makes a list of meetings that need mentors. Finally it stores the list of people who need to be notified about closing meetings and meetings that need more mentors and stores them in a report folder.

	-RemoveMentor.php and RemoveMentee.php
These files are similar in function. They both work by taking in the student Id, meeting Id, and if they are to be removed from all recurring meetings or a singular meeting. There are checks to make sure the student is in the meeting and it is not too late to exit the meeting. The function removes the student, then checks to remove them from the mentee or mentor table based on whether they are participating in any meetings.
	
	-DatabaseInitaliser.html
The database initializer homescreen. Choose test to create a database that has some test values in it. Choose empty to create an empty database with just an admin account.
