###This example covers Facebook Login with the Facebook SDK for PHP.
Source URL: https://developers.facebook.com/docs/php/howto/example_facebook_login

There is requirement to have a callback endpoint. Current implementation uses fb-callback endpoint;
Callback should be also registered on **App dashboard** (https://developers.facebook.com/apps). choosing your app and going to 

**Products** > **Facebook Login** > **Settings**

under the Client OAuth Settings and entering full path (i.e. https://localhost/fb-callback)

###User links
According to documentation *publish_actions* is needed in order to post links. Unfortunately *publish_actions* permission is deprecated since August 1, 2018. No further apps will be approved to use *publish_actions* via app review. 

Developers currently utilizing *publish_actions* are encouraged to switch to Facebook's Share dialogs for web, iOS and Android. source :

https://developers.facebook.com/blog/post/2018/04/24/new-facebook-platform-product-changes-policy-updates/
