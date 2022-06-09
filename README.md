# File manager app

* Application which allows the user to browse and manage files in computer local directory. The app simply serves as a file manager application.

![application_screenshot](https://user-images.githubusercontent.com/97868964/172853331-748b69ff-2f57-4610-b099-459e0a45acf5.jpg)

* Project was done as a Sprint 6 task while I was studying at Baltic Institute of Technology. 

## Getting Started

* To be able to use the app, you must first have "Apache" server application like "XAMPP" or similar web server app. You can find more info about "XAMPP" [here](https://www.apachefriends.org/)

If you are using XAMPP:

* Clone this repository to .../xammp/htdocs/ directory (or download the files manually).

* Start "Apache" server in "XAMMP".

* Open your prefered browser and go to localhost/File_manager_php/

Note that the app name in the URL must be the same as the directory name where "index.php" file is located. If directory name is changed, change URL accordingly. 

* Lastly, log in using given user name and password: 

  User name: User

  Password: 12345

![login_screenshot](https://user-images.githubusercontent.com/97868964/172853554-ed25ade0-9bd9-40df-8816-3d12b91e809b.jpg)


## Description

* This app is password protected, so the user must first log in to be able to use the app. 

* The app is scaning all the files and folders starting from a directory where index.php file containing app script is located. 

* It allows the user to see the file type, file size and file extensions. 

* Also the user is able to download or delete chosen files excluding "index.php" and "styles.css" files.

* There is an option to delete folders also, but the folder must be empty, so all the files inside that folder must be deleted first.
Button for folder deletion will be shown besides the empty folders only.

* The user is also able to create new files and folders. 

New file is created after entering the file name in the "Create new file" input field and pressing "Create file" button. But the entered file name must contain file extension like ".txt", ".jpg" etc. 

Folder is created if the entered file name does not have an extension. 

* Also there is an option to upload a chosen file from the computer by simply pressing "Choose file" button and selecting the file to upload.


## Techniques used

Code is written in PHP.

Styled using raw CSS.

Added FontAwesome icons.

## Authors

Project made by me - Žygimantas Kairaitis. 

Find me on [LinkedIn](https://www.linkedin.com/in/%C5%BEygimantas-kairaitis-018a86193/)
