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

Add the required file in the CRM spring boot app to make it 


/* ================================================================== */

I want you to create a multiple CSV import on my Spring Boot Web app with MySQL db, here are the constraint and instructions:

There is 2 CSV file, here are a sample data from it: 
file-1: 

```
customer_email,subject_or_name,type,status,expense
customer1@yopmail.com,Lorem ipsum,lead,meeting-to-schedule,150000
mail2@test.com,Correction bug #123,ticket,open,95000
customer1@yopmail.com,Test avant,lead,archived,"68500,87"
```

file-2:

```
customer_email,customer_name
customer1@yopmail.com,john doe
mail2@test.com,jane smith
```

The file-1 contains data that needs to be inserted into `Ticket`, `Lead` and `Expense`
The file-2 contains data that needs to be inserted into `Customer`

# Instructions and constraints:

- The multiple CSV import needs to be in a single page 
- The number of <input> depends on the number of CSV file (in our case 2)
- One single button to submit all csv files
- If there are errors in the CSV file: 
    - the mecanism of insertion needs to follow the principe of `Tout ou rien`
        - if a single errors appears/exist in one of the CSV, no data is inserted
        - all data needs to be correct and valid to allow insertion data
    - You need to display the CSV filename causing the error
    - You need to display line number causing the error
    - Display the type of the errors (invalid date, negatif number, missing value, incorrect value ...)
    - If there is missing values on the given CSV file, you need generate the missing values 
        - use datenow() if it it's a date value
        - use random string respecting the lenght if it is string
        - respect the values if it is has a specific patterns 
    - A single CSV file may contains several tables columns
        - example: the data in file-1.csv contains data that needs to be inserted `Customer` and `Expense` table
    - You need to manage to order of insertion based on the file because all file is submitted with a single button
        - for instance in our case, the file-2 needs to be inserted first because it contains the data of the Customers which is an FK on the table Ticket and Lead

Here are the entities and tables concerned: 
```
package site.easy.to.build.crm.entity;

[import ...]

@Entity
@Table(name = "trigger_ticket")
public class Ticket {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "ticket_id")
    private int ticketId;

    @Column(name = "subject")
    @NotBlank(message = "Subject is required")
    private String subject;

    @Column(name = "description")
    private String description;

    @Column(name = "status")
    @NotBlank(message = "Status is required")
    @Pattern(regexp = "^(open|assigned|on-hold|in-progress|resolved|closed|reopened|pending-customer-response|escalated|archived)$", message = "Invalid status")
    private String status;

    @Column(name = "priority")
    @NotBlank(message = "Priority is required")
    @Pattern(regexp = "^(low|medium|high|closed|urgent|critical)$", message = "Invalid priority")
    private String priority;

    @ManyToOne
    @JoinColumn(name = "manager_id")
    private User manager;

    @ManyToOne
    @JoinColumn(name = "employee_id")
    private User employee;

    @ManyToOne
    @JoinColumn(name = "customer_id")
    private Customer customer;

    @ManyToOne
    @JoinColumn(name = "expense_id")
    private Expense expense;

    @Column(name = "created_at")
    private LocalDateTime createdAt;

    [getter and setters ...]
}

package site.easy.to.build.crm.entity;

[import ...]

@Entity
@Table(name = "trigger_lead")
public class Lead {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "lead_id")
    private int leadId;

    @Column(name = "name")
    @NotBlank(message = "Name is required")
    private String name;

    @Column(name = "status")
    @NotBlank(message = "Status is required")
    @Pattern(regexp = "^(meeting-to-schedule|scheduled|archived|success|assign-to-sales)$", message = "Invalid status")
    private String status;

    @Column(name = "phone")
    private String phone;

    @Column(name = "meeting_id")
    private String meetingId;

    @Column(name = "google_drive")
    private Boolean googleDrive;

    @Column(name = "google_drive_folder_id")
    private String googleDriveFolderId;

    @OneToMany(mappedBy = "lead", cascade = CascadeType.ALL)
    private List<LeadAction> leadActions;

    @OneToMany(mappedBy = "lead", cascade = CascadeType.ALL)
    private List<File> files;

    @OneToMany(mappedBy = "lead", cascade = CascadeType.ALL)
    private List<GoogleDriveFile> googleDriveFiles;

    @ManyToOne
    @JoinColumn(name = "user_id")
    private User manager;

    @ManyToOne
    @JoinColumn(name = "employee_id")
    private User employee;

    @ManyToOne
    @JoinColumn(name = "customer_id")
    private Customer customer;

    @ManyToOne
    @JoinColumn(name = "expense_id")
    private Expense expense;

    @Column(name = "created_at")
    private LocalDateTime createdAt;

    [getter and setters ...]
}

package site.easy.to.build.crm.entity;

[import...]

@Entity
@Table(name = "expense")
public class Expense {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "expense_id")
    private int expenseId;

    @Column(name = "amount", nullable = false)
    private double amount;

    @Column(name = "expense_date", nullable = false)
    private LocalDate expenseDate;

    [... getter and setter]
}


@Entity
@Table(name = "customer")
public class Customer {

    public interface CustomerUpdateValidationGroupInclusion {}
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "customer_id")
    private Integer customerId;

    @Column(name = "name")
    @NotBlank(message = "Name is required", groups = {Default.class, CustomerUpdateValidationGroupInclusion.class})
    private String name;

    @Column(name = "email")
    @NotBlank(message = "Email is required")
    @Email(message = "Please enter a valid email format")
    @UniqueEmail
    private String email;

    @Column(name = "position")
    private String position;

    @Column(name = "phone")
    private String phone;

    @Column(name = "address")
    private String address;

    @Column(name = "city")
    private String city;

    @Column(name = "state")
    private String state;

    @Column(name = "country")
    @NotBlank(message = "Country is required", groups = {Default.class, CustomerUpdateValidationGroupInclusion.class})
    private String country;

    @Column(name = "description")
    private String description;

    @Column(name = "twitter")
    private String twitter;

    @Column(name = "facebook")
    private String facebook;

    @Column(name = "youtube")
    private String youtube;

    @ManyToOne
    @JoinColumn(name = "user_id", nullable=false)
    @JsonIgnoreProperties("customer")
    private User user;

    @OneToOne
    @JoinColumn(name = "profile_id")
    @JsonIgnore
    private CustomerLoginInfo customerLoginInfo;

    @Column(name = "created_at")
    private LocalDateTime createdAt;
```

I need you to create the service class containing the methods managing the importation. Create a controller and a simple thymeleaf views for teh single formulaire for the importation. 

My controller package is: `package site.easy.to.build.crm.controller`
My service package is for example : `package site.easy.to.build.crm.service.expense`


/* ========================================================= */

here are a little adjustement that you need to do: 

For the customer :
    - generate random valid values for those columns instead of inserting NULL
        - phone, address, city, state, description, position, twitter, facebook, youtube

For the Ticket:
    - add a random string in description instead of NULL
    - ive added this attributes on the Service class :
        - private final int USER_ID = 52; // fix value because `users` table can't be deleted 
    - use it on the columns `manager_id` 

For the Lead:
    - add USER_ID on `user_id` column
    - generate a random value of phone


/* ========================================================= */

Give this CSV import service class, i need you to create CSV data to test if the error handling is working well
i need you to create multiple CSV file and predict the scenario based on our validation rules, add all edge cases of error according to our rules 



Here are the file that i use for an import functionality in my spring boot app:
Is everything clear and do you understand what i do ?


/* ========================================================= */

Given those file in a spring boot app who manage a functionality of multiple CSV import, 
i want you to add this :

i have 2 file to be imported, i want to modify the thymeleaf view by adding a third input for a third CSV file.
The third csv file will contains data that needs to be inserted in the table `CustomerBudget`.

here are the concerned entity: 
```
@Entity
@Table(name = "customer_budget")
public class CustomerBudget {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "budget_id")
    private Integer budgetId;

    @ManyToOne
    @JoinColumn(name = "customer_id")
    @NotNull(message = "Customer is required")
    private Customer customer;

    @Column(name = "label")
    @NotBlank(message = "Label is required")
    private String label;

    @Column(name = "amount")
    @NotNull(message = "Amount is required")
    private BigDecimal amount;

    @Column(name = "transaction_date")
    @NotNull(message = "Transaction date is required")
    private LocalDate transactionDate;

    @Column(name = "created_at")
    private LocalDateTime createdAt;

    @Column(name = "updated_at")
    private LocalDateTime updatedAt;

    @ManyToOne
    @JoinColumn(name = "user_id")
    private User user;

... 

CREATE TABLE IF NOT EXISTS `customer_budget` (
  `budget_id` int unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`budget_id`),
  KEY `fk_budget_customer` (`customer_id`),
  KEY `fk_budget_user` (`user_id`),
  CONSTRAINT `fk_budget_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  CONSTRAINT `fk_budget_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

```
By adding this third file, here are the order when processing the file, first process the customer csv file, then process the customer budget file and then process the csv file with tickets and leads.
you need to follow the same rules and same principles ("tout ou rien", error validation)

here are the instructions: 
- generate a random string for label
- for transaction_date and created_at use date_now()
- use the id of the fixed user id for user_id 


/* ====================================================== */

In my REST API laravel web app, display on the dahsboard the budget and expense total using this api endpoint : 

- GET http://127.0.0.1:8080/api/dashboard/total_expenses
- the returned JSON data:

{
    "expenses": 313500.87
}

- GET http://127.0.0.1:8080/api/dashboard/total_budget
- the returned JSON data:

{
    "totalBudget": 2600000.00
}

here is the concerned code part : 
