# Beauty Salon Pricing

This is a repository for a beauty salon (product and service) pricing list, built with Laravel 5.3 and Vue 2.

## Functionality
- Allows user to register and create products and services.
- Each User may have many products and services.
- Each Product and Service belong to one User.
- Products are defined by a ```user_id```, ```brand```, ```name``` and ```price```.
- Services are defined by a ```user_id```, ```title``` and ```price```.
- Products and Services, once created, can be edited and deleted via the 'backend' UI.
- All products and services are available for viewing, in a price listing UI, seperate to the 'backend' view.
- The price listing view has the option to add a quantity of items to the total price, as well as removing a specified quantity of the item.
- Products and Services may be sorted by brand and title, respectively.

*Note, the user registration controller currently restricts registration to only one user, as per the project specifications. To remove this restriction, simply remove the ```no_accounts``` validation rule in ```App\Http\Controllers\Auth\RegisterController.php#52```.*

##Custom Validation rules
As well as the ```no_accounts``` validation rule, used in the ```RegisterController```, two other custom validation rules, ```dollars``` and ```allowed_dollar_amount```.
The ```dollars``` validation rule uses the following pattern: ```/^\d{0,5}(\.\d{2})?$/```, to verify that a value is in the following format: ```123.45```.
The ```allowed_dollar_amount```  uses the following pattern: ```/^\d{0,5}(\.\d{2})?$/```, to verify that a value is has a maximum of 5 whole numbers, preceeding the ```.``` and two decimal places.

## Screenshot
![Product and Service pricelist](https://cloud.githubusercontent.com/assets/9494635/20696758/3e09101a-b645-11e6-9a21-ac85d8a55e8e.PNG)
The price list in use.

## Installation & Configuration
If you would like to install this project, treat it as you would any other Laravel application, keeping in mind some additional crucial environment variables:
- ```APP_URL``` : the url of the application. This variable is used to link to the application.
- ```MAIL_FROM_EMAIL``` and ```MAIL_FROM_NAME```: the 'from' email address and name. This is used for sending out emails.
- ```COPY_NAME``` and ```COPY_URL```: the text and link in footer, used for dev details.

*Remember to either remove references to the legal views routes, or add in your own views under ```legal.terms``` and ```legal.privacy```*

Further steps:
- Set the ```APP_ENV``` environment variable to ```production``` when the app is on a live sever, to force HTTPS connections on all routes.

*Please do not use the associated legal views, if they still exist in this repository (terms.blade.php and privacy.blade.php). Use at your own peril.*
