**Project Overview:**  
I am working on a large open source repositorie of a **CRM application** built with **Spring Boot MVC** and i want to extend his functionality by developing a **new web application** that communicates with the CRM via **REST APIs**.  

**Task:**  
Implement **REST API-based login** in the new app while adhering to the following constraint:  
- The new app **cannot** interact directly with the main database for authentication.  
- Instead, it must authenticate users by calling the **Spring Boot CRM's login API**.  

**Tech Stack:**  
- **Existing CRM App:** Spring Boot MVC, Mysql for database  
- **New App:** Laravel 10  

**Question:**  
How can I implement a **login** in my Laravel 10 app using the CRM’s REST API for authentication?  

Below is the **relevant code** from the CRM to help you understand the authentication flow and guide the implementation in Laravel.  

Create a REST API method in laravel to login using the link `route` in the form on `login.html`, returns as a JSON all o fthe necessary infomations to handle authentication on my new laravel app. 

Add the required file in the CRM spring boot app to make it work