# FieldLedger

**FieldLedger** is a mobile-friendly construction field operations web
application developed by **Lacey Roof** as an ECPI University Software
Development capstone project in 2026.

The project was inspired by a real construction workflow in which
foremen rely heavily on paper daily reports and may complete
administrative reporting after leaving the jobsite. FieldLedger brings
estimating, job information, field reporting, review, and management
visibility into one centralized application.

**Sample company:** Sier Site Grading\
**Application:** FieldLedger\
**Developer:** Lacey Roof\
**Year:** 2026

## Project Purpose

FieldLedger demonstrates how a web application can replace disconnected
paper processes with a shared operational system. The application
follows a construction job from estimating through active field work and
completion, with different functions available based on user role.

A major design principle that developed during the project was:

**Estimator determines cost. Executive determines price. Users report what they know. FieldLedger calculates what it can.**

## Core Features

### Role-Based Access
Current roles are **Foreman, Estimator, Admin, and Executive**. 
Foremen: create daily reports; Estimators build jobs and estimates; 
Admin: support operational administration and review; 
Executives: review financial/project information and approve pricing. 

Sensitive estimate costs are restricted from Foremen.
Executive Dashboard is restricted to Admin and Executive users.

### Job Workflow
The workflow evolved during development into:

**Estimating → Pending Exec Approval → Pending Client Approval → Active
/ Declined → Complete**

Jobs may also be archived instead of permanently deleted.

### Estimating
Estimates contain tasks, duration, labor hours/cost, equipment
hours/cost, material cost, production quantities, units, and resources.
Resource quantities and rates are used to calculate costs rather than
requiring users to manually calculate each task.

Estimates begin as **Drafts**. An Estimator can continue building the
estimate until explicitly selecting **Submit for Executive Approval**.

### Executive Approval and Pricing
Pending estimates appear on the Executive Dashboard. FieldLedger
calculates a suggested customer quote using a **20% markup on estimated
cost**, rounded up to the nearest \$1,000. The Executive can review the
job and approve or adjust the quote. The job then moves to **Pending
Client Approval**.

### Daily Reporting
Foremen can record employees/labor hours, equipment, materials,
production quantities, site/weather notes, and task-level notes. Reports
may be saved in progress, submitted, and reviewed.

### Job Performance
FieldLedger compares estimated activity with actual field reporting,
including labor hours, equipment hours/cost, material cost, remaining
amounts, and production-based progress. Detailed financial information
is grouped into a restricted **Job Estimates** area.

### Executive Dashboard
The Executive Dashboard includes active jobs, active contract value,
pipeline value, pending approvals, and projected margin by active
project.

## Capstone Project Specifications
FieldLedger was designed to satisfy the technical and business requirements established for the Software Development Capstone.

### Customer and End Users
FieldLedger was developed as a proposed solution for Sier Site Grading, a sample construction/site-grading organization. 
The system is designed for the organization's employees rather than public customers.
Four primary end-user roles are supported: Foreman, Estimator, Admin, and Executive. 
Each role has access to functions appropriate to its responsibilities.

### Multi-Page Web Application
FieldLedger exceeds the requirement for five distinct pages. 
Major application pages include the Dashboard, Jobs, Job View, New Estimate, Daily Report, Reports/Review, Administration, and Executive Dashboard.

### Relational Database
The application uses a MySQL/MariaDB relational database containing more than five related tables. 
These include Users, Employees, Jobs, Estimates, Job Tasks, Resources, Task Resources, Daily Reports, Daily Task Entries, and Labor Entries.
Primary and foreign-key relationships connect operational information across the application.

### Cloud Hosting
FieldLedger was developed and tested locally using XAMPP and deployed to InfinityFree for cloud hosting. 
The hosted version allows the application to be accessed through a web browser rather than being limited to the development computer.

### Secure Software
Several security measures were incorporated into the application:
- User authentication and PHP sessions
- Password hashing
- Prepared SQL statements
- `htmlspecialchars()` when displaying user/database content
- Centralized role-based permission functions
- Page-level authorization
- Restricted financial and estimating information
- Executive Dashboard access limited to Admin and Executive users
Permissions are enforced by the application rather than relying only on hidden navigation links. 
For example, an unauthorized user attempting to directly access the Executive Dashboard is redirected.

### Realistic User Interface and Data
FieldLedger was designed to resemble a usable internal business application rather than a demonstration containing placeholder information.
The database contains realistic sample construction projects, customers, employees, labor rates, equipment, materials, estimates, production quantities, costs, and daily reporting information.
The interface includes consistent company/application branding, responsive layouts, navigation, dashboards, forms, tables, project statuses, progress indicators, and mobile-friendly sizing.

### Search and Data Retrieval
Users can search for jobs and retrieve project information stored in the database. 
Additional views retrieve information based on job status, report status, approval status, and administrative needs.

### User and Password Management
Because FieldLedger is an internal employee system, public self-registration was intentionally not implemented. 
Allowing anyone to create an account would provide inappropriate access to internal construction and financial information.
Instead, authorized Admin users can create user accounts and manage/reset passwords. This adapts the account-management requirement to the security needs of the customer.

### Data Creation and Modification
FieldLedger is not a read-only reporting application. User actions continually modify application data.
Examples include:
- Creating jobs and estimates
- Adding estimate tasks and resources
- Submitting estimates for Executive approval
- Approving customer quotes
- Changing job and approval statuses
- Creating and submitting daily reports
- Recording employee labor
- Recording equipment, materials, and production
- Reviewing reports
- Creating and managing employees and users
- Completing or archiving records

These changes are stored in the database and subsequently affect job views, dashboards, calculations, approval queues, and reports.

### Customer-Focused Design
FieldLedger was designed around the needs of the customer's employees rather than around technology alone. 
Foremen, Estimators, Admin, and Executives have different responsibilities and therefore different information and permissions within the application.
This became an important design principle during development:

## Technologies and Platforms Used
-   **PHP** -- server-side application logic
-   **MySQL / MariaDB** -- relational database
-   **SQL** -- joins, calculations, aggregation, CRUD operations, and reporting
-   **HTML5** -- application structure and forms
-   **CSS3** -- responsive layout and styling
-   **JavaScript** -- interactive forms and interface behavior
-   **Chart.js** -- Executive Dashboard visualizations
-   **XAMPP** -- local Apache/PHP/MariaDB environment
-   **phpMyAdmin** -- database creation, inspection, testing, and maintenance
-   **Visual Studio Code** -- development environment
-   **InfinityFree** -- hosted deployment
-   **Browser developer tools** -- responsive testing and troubleshooting
-   **Git/GitHub concepts and version-control practices** -- prior coursework informed development and file-management practices

Development was performed primarily in the local XAMPP environment before updated application files and database changes were moved to the
hosted version.

## Prior Coursework Applied
FieldLedger allowed me to apply skills developed across several courses in ECPI University's BS Information Technology – Software Development & Coding, AI/ML and Data Analytics track.

- **SDC420 – System Analysis and Design**  
  This course had a direct connection to FieldLedger. 
  I identified the users of the system, defined role-based requirements, mapped the workflow from estimating through job completion, refined business rules, and 
  changed the design when the original workflow did not accurately represent how the work would occur.

- **SDC200 – Introduction to Databases**  
  Applied when designing the relational structure behind FieldLedger. 
  The project uses related tables for users, employees, jobs, estimates, job tasks, resources, daily reports, labor entries, and other operational data.

- **SDC250 / SDC250L – Structured Query Language & Lab**  
  SQL was used extensively throughout FieldLedger to retrieve, insert, update, join, group, and summarize application data. 
  This included estimate totals, job information, actual field activity, approval queues, pipeline values, and Executive Dashboard metrics.

- **SDC310 / SDC310L – Server-Side Scripting with PHP & Lab**  
  PHP became the primary server-side language for FieldLedger. 
  I used it for form processing, database interaction, sessions, login-based access, permissions, calculations, workflow/status changes, and 
  reusable files such as the header, task form, job metrics, and permission functions.

- **SDC355 – JavaScript**  
  JavaScript was used for interactive behavior within the application, particularly where forms needed to dynamically add, remove, show, hide, or modify task and 
  resource entry areas without requiring separate static forms for every entry.

- **SDC260 – Web Interface Design** and **SDC345 / SDC345L – Interface Design I & Lab**  
  These concepts were applied to FieldLedger's page structure, forms, navigation, dashboards, tables, visual hierarchy, and responsive design. 
  I repeatedly adjusted spacing, layouts, navigation, headers, tables, and forms based on how the application behaved at different screen sizes.

- **SDC255 – Introduction to Agile and Scrum Methodologies**  
  FieldLedger was developed iteratively rather than attempting to complete the entire design at once. 
  I built individual features, tested them, identified problems, changed requirements when necessary, and continued refining the system. 
  Several original decisions—including roles, approval statuses, permissions, and estimate workflow—changed during development.

- **SDC340 – Mobile App Development**  
  FieldLedger is not a native mobile application. However, concepts from this course influenced my decision to make the web application responsive and mobile-ready. 
  I used responsive sizing and breakpoints so important field functions can be used on smaller screens if the system is accessed from a phone or tablet.

- **SDC230 / SDC230L – Object-Oriented Programming Using Java & Lab**  
  Although FieldLedger is not written in Java, this coursework strengthened programming fundamentals that carried into the project, 
  including breaking larger problems into smaller pieces, organizing application logic, debugging, and reusing code rather than duplicating it unnecessarily.

- **SDC205 / SDC205L – Python for Data Analytics & Lab** and **SDC380 – Introduction to Data Analytics**  
  FieldLedger is not a Python application, but the analytical concepts from these courses influenced how I approached the data being collected. 
  Instead of only storing information, I used it to compare estimated and actual activity and to create project-level and Executive-level metrics.

- **SDC385 – AI/ML and Data Analytics Tools, SDC485 / SDC485L – AI/ML and Data Analytics Methods and Modeling & Lab, and SDC486L – AI/ML and Advanced Data Analytics Lab**  
  FieldLedger does not currently contain a machine-learning model. These courses instead influenced how I approached reporting and future use of the application's data. 
  The Executive Dashboard uses operational data for visual reporting, and future plans include historical profit and 
  cost comparisons, revenue forecasting, and staffing projections.

FieldLedger required me to combine skills that had previously been practiced separately. 
The project was not simply a PHP assignment, database assignment, interface project, or analytics exercise. 
Changes to one part of the system often affected the database, PHP logic, interface, permissions, calculations, and workflow, 
requiring those areas to work together as one system.

## Generative AI Assistance
Generative AI was used as a development support and learning tool
throughout the project. Assistance included discussing architecture and
workflow options, breaking requirements into smaller tasks, reviewing
and troubleshooting PHP/SQL/HTML/CSS/JavaScript, explaining errors,
suggesting query structures, refactoring repeated components, reviewing
permissions, brainstorming dashboard metrics, and assisting with
documentation.

AI suggestions were reviewed, tested, modified, simplified, or rejected
as needed. Project requirements, workflow decisions, database changes,
interface direction, business rules, testing decisions, and final
implementation were developed iteratively by the developer rather than
generated as a completed application in one step.

An important part of using Generative AI was keeping the final code
understandable. Suggestions that were unnecessarily complex or
inconsistent with the existing application were simplified so the
implementation remained explainable and aligned with concepts learned in
the program.

## Major Challenges

### Database Stability
A significant challenge involved a local XAMPP/MariaDB crash involving
InnoDB/phpMyAdmin data. Troubleshooting required isolating the affected
data, rebuilding supporting files, confirming database stability, and
creating/verifying a database backup.

### Keeping Database and Workflow Logic Synchronized
As the workflow became more realistic, database fields and statuses
changed. Estimate approval status and job status had to remain
synchronized. A longer status value also exceeded the existing database
field size during testing, producing a MySQL truncation warning; the
status was shortened to **Pending Exec Approval**.

### Reusable Components
Task entry was needed in multiple workflows. Converting repeated code
into reusable components reduced duplication but introduced challenges
involving variables, resource result sets, form modes, and JavaScript
behavior.

### PHP Sessions, Includes, and Redirects
The project reinforced several PHP requirements: sessions must start
before session-based permission checks; redirects must occur before HTML
output; include paths must remain reliable; and nested `if/endif` blocks
must be carefully closed when restricting page sections.

### MySQL Result Sets
The Job View displays tasks in both a general task table and a
restricted financial estimate table. The second table initially appeared
empty because the first loop had already consumed the MySQL result set.
Resetting the result pointer allowed the same data to be displayed
again.

### Responsive Design
The application needed to work on both desktop and mobile devices.
Navigation, logos, tables, cards, forms, job headers, status labels, and
spacing required repeated adjustment to remain usable on smaller
screens.

### Scope Management
One of the largest challenges was deciding what belonged in a five-week
capstone. Several worthwhile ideas were intentionally deferred so the
core workflow could be completed, tested, and explained rather than
continually expanding the project.

## Important Design Changes

### Project Manager Role Removed
An early design included a Project Manager role. It was removed after
reviewing the real workflow being modeled. The final roles are Foreman,
Estimator, Admin, and Executive.

### Explicit Estimate Approval
Creating an estimate originally moved the workflow forward too quickly.
This changed to Draft → explicit **Submit for Executive Approval** →
locked editing during approval.

### Cost and Customer Price Separated
The Estimator develops expected project cost. The Executive determines
customer price. Suggested pricing currently uses a 20% markup and rounds
upward to the nearest \$1,000.

### Delete Changed to Archive
Archiving replaced permanent deletion where appropriate so historical
records can be retained.

### Financial Information Restricted
Foremen need production and operational information but do not
necessarily need selling price, profit, margin, or detailed estimate
costs. Role-based permissions were expanded accordingly.

### Executive Reporting Added
The Executive Dashboard evolved from a general management page into a
financial view focused on contract value, pipeline, approvals, and
project margin.

## Current Project Structure
``` text
FieldLedger/
├── Assets/
│   ├── images/
│   ├── estimate_actions.php
│   ├── footer.php
│   ├── header.php
│   ├── job_metrics.php
│   ├── permissions.php
│   ├── styles.css
│   └── task_form.php
├── Config/
│   └── database.php
├── Views/
│   ├── add_estimate.php
│   ├── admin.php
│   ├── daily_report.php
│   ├── dashboard.php
│   ├── executive.php
│   ├── job_view.php
│   ├── jobs.php
│   └── ...
└── index.php
```

## Future Enhancements

### Executive Analytics
-   Closed jobs and final profit for the previous six months
-   Estimated vs. actual cost trends
-   Actual job cost using labor, equipment, and material activity
-   Revenue forecasting
-   Staffing forecasts based on upcoming work
-   Budget alerts for jobs approaching or exceeding estimates
-   Embedded Power BI reporting

### Field Reporting
-   More detailed equipment/resource tracking
-   Material quantities with automatic cost calculation
-   Overtime-rate calculations
-   Jobsite photos and attachments
-   Digital signatures
-   Offline/mobile reporting for poor-connectivity jobsites

### Communication
-   User messaging
-   Task/report comments
-   Requests for additional information
-   Approval/review notifications

### Estimating and Financial Management
-   Estimate version history
-   Change orders
-   Contingent items and extra-work authorization
-   Production-rate analysis
-   Estimated vs. actual performance by task
-   Historical cost information to improve future estimates

### Administration and Security
-   More granular permissions
-   Audit history
-   Password recovery
-   Stronger production authentication/security controls
-   User activity history and additional retention controls

### Reporting and Integration
-   PDF daily reports
-   Excel/CSV exports
-   Customer-facing reports
-   Accounting/payroll integrations
-   APIs for external construction/business systems

## What I Learned
The largest lesson from FieldLedger was that building an application is
not primarily about writing individual pieces of code. The more
difficult work was defining how the business process should function and
ensuring that the database, interface, permissions, calculations, and
user workflow all supported the same rules.

The project also reinforced that software design is iterative. Several
of the strongest parts of the final application came from recognizing
that an earlier approach did not accurately represent the workflow and
changing it.

FieldLedger brought together database design, SQL, PHP, front-end
development, responsive design, access control, business analysis,
testing, troubleshooting, and documentation in one project. It also
provided practical experience using Generative AI as a development
assistant while retaining responsibility for understanding, testing, and
making final implementation decisions.

## Disclaimer
FieldLedger is an educational capstone project. **Sier Site Grading** is
used as sample company/branding for the application. The system is not
presented as production-ready construction, accounting, payroll, or
financial software.

------------------------------------------------------------------------

**Lacey Roof © 2026 --- FieldLedger**
