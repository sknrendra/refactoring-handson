# Refactoring Handson Exercise

Install and Run
1. With docker: docker compose up
2. Without docker: composer install && ./vendor/bin/phpunit tests

The exercise

Update the test new so the messages returned from processUser() are:

1. for Admins & Managers logged in recently (30 days):

"Welcome back, [name]. You have full access."

2. Admins & Managers inactive for over 30 days:

"Your access is temporarily limited. Please log in soon."

3. No login record:

"Please complete your first login."

4. Guests:

"Welcome, guest! Signed up on [signup_date]."

5. Unknown roles:

"Access denied. Role not recognized."

6. Notifications are no longer mentioned.

