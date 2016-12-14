# Beauty Salon Pricing

This is a repository for a beauty salon (product and service) pricing list, built with Laravel 5.3 and Vue 2.

##Highlights
The application uses Factal, via the ```spatie/laravel-fractal``` package and has been utilized to create an internal API for the retrieval of a complex data structure.
The data is accessible by registered users at ```/products/fetch``` and ```/services/fetch```.

Vue has been used to create the reactive front-end components that utilize the API.

## Functionality
- Allows user to register and create products and services.
- Services are defined by a ```title``` , ```category``` and ```price```.
- Products are defined by a ```brand```, ```name```, ```category``` and ```price```.
- Products and Services, once created, can be edited and deleted via the 'backend' UI (```/products``` or ```/services```).
- All products and services are available for viewing, in a price listing UI (```/prices/services```, ```prices/products``` or ```/prices/all```), seperate to the 'backend' view. *```/prices/all``` being a view including all products & services.*
- The price listing view has the option to add a quantity of any item (```Product``` or ```Service```) to the total price, as well as removing a specified quantity of the item from the total price.
- Products and Services may be sorted by brand and category, respectively, in the 'backend' view, as well as the pricing list view.
- Products and Services may be discounted via the UI, using predefined values as per the project specifications.

*Note, the user registration controller currently restricts registration to only one user, as per the project specifications. To remove this restriction, simply remove the ```no_accounts``` validation rule in the [RegisterController](App\Http\Controllers\Auth\RegisterController.php#L5).*

##Custom Validation rules
As well as the ```no_accounts``` validation rule, used in the ```RegisterController```, two other custom validation rules have been implemented, ```dollars``` and ```allowed_dollar_amount```.

The ```dollars``` validation rule uses the following pattern: ```/^\d{0,5}(\.\d{2})?$/```, to verify that a value is in the following format: ```123.45```.

The ```allowed_dollar_amount```  uses the following pattern: ```/^\d{0,5}(\.\d{2})?$/```, to verify that a value has a maximum of 5 whole numbers, preceeding the ```.``` and two decimal places.

## Installation & Configuration
If you would like to install this project, treat it as you would any other Laravel application, keeping in mind some additional crucial environment variables:
- ```APP_URL``` : the url of the application. This variable is used to link to the application.
- ```MAIL_FROM_EMAIL``` and ```MAIL_FROM_NAME```: the 'from' email address and name. This is used for sending out emails.
- ```COPY_NAME``` and ```COPY_URL```: the text and link in footer, used for dev details.

*Remember to either remove references to the legal views routes, or add in your own views under ```legal.terms``` and ```legal.privacy```*

Further steps:
- Set the ```APP_ENV``` environment variable to ```production``` when the app is on a live sever, to force HTTPS connections on all routes.

## Screenshots
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

###Pricing lists
Pricing list (top)
![All Prices- top](https://cloud.githubusercontent.com/assets/9494635/20857450/faf54d4e-b97c-11e6-9fd1-3069b4685238.PNG)

Pricing list (bottom)
![All Prices- bottoml](https://cloud.githubusercontent.com/assets/9494635/20857451/fd68f99a-b97c-11e6-978a-ac72544d0fd1.PNG)

##Routes
![Routes](https://cloud.githubusercontent.com/assets/9494635/21089629/b1216afe-c089-11e6-90cc-e17efed057a4.PNG)
Thanks to [Pretty Routes](https://github.com/garygreen/pretty-routes)

##Packages
- [Laravel Fractal](https://github.com/spatie/laravel-fractal)

##Disclaimer
- Prices, within the database and Vue components are of type ```float```, feel free to enhance the trailing points' accuracy by changing the type to ```double```. However, I have chosen to stick with ```float``` type, as there is no real advantage, considering the limitations in rounding up floating point numbers to the nearest floating point value (, given the number of trailing decimals to keep intact) in JavaScript.
- The ```price``` field in the ```products``` and ```services``` tables are restricted to values no greater than ```99999.99```(7 digits, 2 of which are trailing decimals).
- It is possible for total prices, shown in footer at ```/prices/all```, ```/prices/services``` and ```/prices/products``` to be off by, at most, 1/2 cent. This is due to prices not being rounded up before being displayed. Instead, total prices are 'trimmed' down with ```n.toFixed(2)```. I have also removed any possible ```-``` preceeding the total price. A preceeding ```-``` may appear, if all items are removed from the total. The resulting code is ```n.toFixed(2).replace('-', '')```.

*Please do not use the associated legal views, if they still exist in this repository (terms.blade.php and privacy.blade.php). Use at your own peril.*
