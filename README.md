# project
1. install xampp in your device and open MyPHPAdmin.

2. create a database named code.

3. create 3 tables --> admin, user_form, and program.

4. create table admin using the query
    CREATE TABLE admin (
    admin VARCHAR(25) PRIMARY KEY,
    password VARCHAR(25)
    );
   
5. create table user_form using the query
   CREATE TABLE user_form (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    password VARCHAR(255)
    ) ENGINE=InnoDB;
   
7. create table program using query
   CREATE TABLE program (
    p_id INT AUTO_INCREMENT PRIMARY KEY,
    html_code VARCHAR(255),
    css_code VARCHAR(255),
    js_code VARCHAR(255),
    u_id INT,
    FOREIGN KEY (u_id) REFERENCES user_form (id)
    ) ENGINE=InnoDB;
   
9. run using your desired localhost after configuring in the xampp.



Well i have written the admin page code to update and delete the user details(CRUD operations)
--> remember that admin has no authority to change users details well the admin can only view and delete user details
-->but i haved given full permissions to the admin

(IF YOU ARE USING THE CODE THEN CHANGE THE ADMIN'S PROPERTIES OF PERFORMING ONLY READ AND DELETE OPERATION)

WELL YOU CAN CREATE NEW ADMINS ON THE DASHBOARD BY ADDING NEW PHP CODE

Thats all..........
