# Beauty Salon Pricing

This is a repository for a beauty salon (product and service) pricing list, built with Laravel 5.3 and Vue 2.

##Highlights
The application uses Factal, via the ```spatie/laravel-fractal``` package and has been utilized to create an API for the retrieval of a complex data structure in a nested array like syntax.
The data is accessible by registered users at ```products/fetch``` and ```services/fetch```.

Vue has been used to create the reactive front-end components that utilize the API.

## Functionality
- Allows user to register and create products and services.
- Each User may have many products and services.
- Each Product and Service belongs to one User.
- Services are defined by a ```user_id```, ```title``` , ```category``` and ```price```.
- Products are defined by a ```user_id```, ```brand```, ```name```, ```category``` and ```price```.
- Products and Services, once created, can be edited and deleted via the 'backend' UI (```/products``` or ```/services/```).
- All products and services are available for viewing, in a price listing UI (```/prices/services```, ```prices/products``` or ```prices/all```), seperate to the 'backend' view. *```/prices/all``` being a view including all products & services.
- The price listing view has the option to add a quantity of items to the total price, as well as removing a specified quantity of the item from the total price.
- Products and Services may be sorted by brand and category, respectively, in the 'backend' view, as well as the pricing list view.
- Products and Services may be discounted via the UI, using predefined values as per the project specifications.

*Note, the user registration controller currently restricts registration to only one user, as per the project specifications. To remove this restriction, simply remove the ```no_accounts``` validation rule in the [RegisterController](App\Http\Controllers\Auth\RegisterController.php#L5).*

##Custom Validation rules
As well as the ```no_accounts``` validation rule, used in ```RegisterController```, two other custom validation rules have been implemented, ```dollars``` and ```allowed_dollar_amount```.

The ```dollars``` validation rule uses the following pattern: ```/^\d{0,5}(\.\d{2})?$/```, to verify that a value is in the following format: ```123.45```.

The ```allowed_dollar_amount```  uses the following pattern: ```/^\d{0,5}(\.\d{2})?$/```, to verify that a value is has a maximum of 5 whole numbers, preceeding the ```.``` and two decimal places.

## Screenshot
###Adding Items
Adding services 'backend'
![Adding Services](https://cloud.githubusercontent.com/assets/9494635/20857457/4d1edb12-b97d-11e6-94c3-de4fb7fc39d3.PNG)
Adding products 'backend'
![Adding Products](https://cloud.githubusercontent.com/assets/9494635/20857458/51647ede-b97d-11e6-88f5-998012b6b912.PNG)

###Backend Listings
Product listings
![Product Listings](https://cloud.githubusercontent.com/assets/9494635/20857448/f3fc4bfa-b97c-11e6-90bd-a141a8df4cbe.PNG)
Service listings
![Service Listings](https://cloud.githubusercontent.com/assets/9494635/20857449/f7a92a20-b97c-11e6-88f9-2f378780ff43.PNG)

####Pricing list
Pricing list
![All Prices- top](https://cloud.githubusercontent.com/assets/9494635/20857450/faf54d4e-b97c-11e6-9fd1-3069b4685238.PNG)
![All Prices- bottoml](https://cloud.githubusercontent.com/assets/9494635/20857451/fd68f99a-b97c-11e6-978a-ac72544d0fd1.PNG)

## Installation & Configuration
If you would like to install this project, treat it as you would any other Laravel application, keeping in mind some additional crucial environment variables:
- ```APP_URL``` : the url of the application. This variable is used to link to the application.
- ```MAIL_FROM_EMAIL``` and ```MAIL_FROM_NAME```: the 'from' email address and name. This is used for sending out emails.
- ```COPY_NAME``` and ```COPY_URL```: the text and link in footer, used for dev details.

*Remember to either remove references to the legal views routes, or add in your own views under ```legal.terms``` and ```legal.privacy```*

Further steps:
- Set the ```APP_ENV``` environment variable to ```production``` when the app is on a live sever, to force HTTPS connections on all routes.

##Packages
- [Fractal](https://github.com/spatie/laravel-fractal)

##Disclaimer
- Prices, within the database and Vue components are ```float```s, feel free to enhance trailing points' accuracy by changing the type to a ```double```. However, I have chosen to stick with ```float``` type, as there is no real advantage, considering the limitations in rounding up floating point numbers in JavaScript as expected.
- Prices are also saved in the database, at a maximum input of 99999.99, as per the specifications of the project, this was all that was required.
- It is possible for total prices, shown in footer at ```/prices/all```, ```/prices/services``` and ```/prices/products``` to be off by, at most, 1 cent. I.e 29.987 will be presented as 29.98. Keep this in mind. This is basically due to the limitations in JavaScript towards rounding up floating point numbers as expected.

*Please do not use the associated legal views, if they still exist in this repository (terms.blade.php and privacy.blade.php). Use at your own peril.*
