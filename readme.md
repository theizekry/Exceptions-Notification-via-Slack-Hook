### Send Push Notification to our Slack Channel whenever any Exception occurred.

##### This Snippet that provider you to catch Exceptions occurred in your project and push notification to Slack Channel             contain that Exception details based in your Slack web hook URL.


[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

# Challenge details and What is required? 
 - We need to know whenever any Exception has been occurred Via slack notification. 
 - We need to custom Slack notification Message and what are we need excatly to show in that message such as
    - HTTP Status Code whenverver available ( Available only if excption instance of HTTP) otherwise get Excption Code,
    - Trace as string.
    - Line Number has that Error.
    - May be request Headers if needed.
    - Request Data.
    - Endpoint URI related by that Exception. 
    - HTTP Method and more. 
- Customize all of above options easily to controle what we need to push and render to our Slack Notification.
- we need to something that we can used it to adding an Exceptions type that we are do not need any exceptions              notifications form those type. in another word built something to avoid send notification and don not report us if we     adding that type there otherwise notify us.
 
## Built With

* [Laravel](https://laravel.com/) - As PHP web framework used. 
* **Events.**
* **Listeners.**
* **Slack Web Hook Integration.**
* **Configuration File.**


## License
Free to use. 
