How to run:

*   git clone [https://github.com/kundu/php-exercise.git](https://github.com/kundu/php-exercise.git)
*   Rename example.env to .env \[root folder\] 
*   run command: composer install , php artisan serve 

> You need to change the app env to production to send emails to users. Because we are using here _QUEUE\_CONNECTION=sync_ and it is sending mail in run time & takes time. Change the email configuration to check the email function

```plaintext
this .evn variables value has been shared through email 
RAPID_FINANCE_API_KEY
RAPID_FINANCE_API_HOST
RAPID_FINANCE_API_BASE_URL
```
