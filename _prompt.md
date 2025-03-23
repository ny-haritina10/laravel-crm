**Project Overview:**  
I am working on a large **CRM application** built with **Spring Boot MVC** and extending its functionality by developing a **new web application** that communicates with the CRM via **REST APIs**.  

**Task:**  
Implement **REST API-based login** in the new app while adhering to the following constraint:  
- The new app **cannot** interact directly with the main database for authentication.  
- Instead, it must authenticate users by calling the **Spring Boot CRM's OAuth2-based login API**.  

**Tech Stack:**  
- **Existing CRM App:** Spring Boot MVC (OAuth2 for authentication), Mysql for database  
- **New App:** Laravel 10  

**Question:**  
How can I implement **OAuth2-based login** in my Laravel 10 app using the CRM’s REST API for authentication?  

Below is the **relevant code** from the CRM to help you understand the authentication flow and guide the implementation in Laravel.  

Here is the button to log via OAuth2:
```
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
        <div class="social">
<!--                            <button class="btn  btn-facebook" data-toggle="tooltip" title="Login with Facebook"> <i aria-hidden="true" class="fab fa-facebook-f"></i> </button>-->
            <button class="btn btn-googleplus" data-toggle="tooltip" title="Login with Google"> <i aria-hidden="true" class="fab fa-google"></i><a th:href="${home + 'oauth2/authorization/google'}"> Login with Google</a></button>
        </div>
    </div>
</div>
```