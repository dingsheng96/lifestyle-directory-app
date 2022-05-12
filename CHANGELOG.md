# Change Log

## 2022-12-05

### What's new?

-   Added system configuration module in admin panel.

### Updates

#### Admin CMS

1. Add total visitor count for each merchant in datatable
2. Include branch created under total listing in dashboard.

#### Merchant CMS

1. Remove about and benefits, remain description, limit 1000varchar, add small(you may include about and benefits here.)
2. Side nav
    - 'my profile' to 'my company profile'
    - 'branch to 'Companies'
3. Once the application being approved, a default branch will be opened under branch with the company details. This details to be set as HQ ad only allow view and edit.
4. Only company name, username, company contact, and password will store in my profile, the rest will be store in the default HQ branch.
5. Add another column in datatables ,title('Company'), inside will store either 'HQ' or "Branch" to indicate .
6. 'Create' btn to 'Create Branches' btn
7. Change email to email(username) in create new branch
8. Move password below listing status in create branch
9. Add another input to add url, name('E-catalogue')
10. Add import function for creating new branch, add default for career introduction, description, services and branch name.

#### Registration Form-Merchant

1. Registration flow -> Fill in the registration form>Store the data>Send verification link via email>Customer fill up and submit>application submit to CMS.
2. New registration form only include, company name, company contact name, email address(username), password, pic name
3. Remaining field to be filled up will be inside the verification form.

## 2021-11-10

### Updates

-   Remove visit count from branch visit histories table
-   Record member visit record every time clicking merchant details from mobile app.

## 2021-11-10

### What's new?

-   Added reordering function for media table in merchant & branch module of admin panel and merchant panel

## 2021-11-09

### What's new?

-   Added review modules in merchant module in admin panel
-   Added review listing and visitor history in merchant panel's dashboard.

### Updates

-   Filter out main merchant's career listing when log in as sub branch in merchant panel.
-   Restrict single customer rate 1 time only to the same merchant.

## 2021-10-11

### Updates

-   Changing form layout of merchant module in admin & merchant panel.
-   Solving the error that cause merchant unable to access dashboard after login and in profile.
-   Solving merchant logout issue.
-   Added state save in data tables.
-   Allow super admin to edit admin user, and restrict other roles cannot edit super admin.
-   Solving the issue of merchant branch listing status unable to store into database.
