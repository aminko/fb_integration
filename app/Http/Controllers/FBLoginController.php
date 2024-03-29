<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Utils\FBPersistentDataHandler;

use Illuminate\Support\Facades\Redirect;
use App\User;
use Auth;

use Facebook;

class FBLoginController extends Controller
{

    /**
     * Initialize Oauth login
     * 
     * User is redirected to Facebook login page where he can login
     * When login action is successful user is redirected to URL provided in callback
     * 
     *  
     *
     * @return void
     */
    public function init() {

        $FBConfig = config('facebook');

        $fb = new Facebook\Facebook([
        'app_id' => $FBConfig['app-id'], // Replace {app-id} with your app id
        'app_secret' => $FBConfig['app-secret'],
        'default_graph_version' => 'v4.0',
        // following parameter required in order to fix issue with session/CSRF
        // Error message - Cross-site request forgery validation failed. Required param "state" missing from persistent data.
        'persistent_data_handler' => new FBPersistentDataHandler(),
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['user_link', 'user_photos', 'user_posts']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($FBConfig['app-callback'], $permissions);

        return redirect()->to($loginUrl);
    }

    /**
     * Authenticate user
     * AccessToken is placed to session store for future [re]use 
     *
     * @return void
     */
    public function login() {

        $FBConfig = config('facebook');

        $fb = new Facebook\Facebook([
            'app_id' => $FBConfig['app-id'], // Replace {app-id} with your app id
            'app_secret' => $FBConfig['app-secret'],
            'default_graph_version' => 'v4.0',
             // following parameter required in order to fix issue with session/CSRF
            // Error message - Cross-site request forgery validation failed. Required param "state" missing from persistent data.
            'persistent_data_handler' => new FBPersistentDataHandler(),
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
        $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
        }

        if (! isset($accessToken)) {
        if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
        }
        exit;
        }

        // Logged in

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($FBConfig['app-id']); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }
        }
        
        // remember access token
        Session::put('fb_access_token', (string) $accessToken);
        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
          
        // Login user and display main page
        $user = User::findOrFail(1);
        Auth::login($user);

        return redirect()->route('home');
    }

    // not possible?
    /**
     * Change profile image
     * 
     * source: https://developers.facebook.com/docs/graph-api/reference/photo
     *
     * @return void
     */
    public function postImage() {

    }

    /**
     * Let logged in user to post link on own Facebook page via Feed dialog
     * 
     *
     * @param Request $request
     * @return void
     */
    public function postMessage(Request $request) {

        $request->validate([
            'link' => 'required',
        ]);

        
        $FBConfig = config('facebook');

        $fb = new Facebook\Facebook([
            'app_id' => $FBConfig['app-id'], // Replace {app-id} with your app id
            'app_secret' => $FBConfig['app-secret'],
            'default_graph_version' => 'v4.0',
            // following parameter required in order to fix issue with session/CSRF
            // Error message - Cross-site request forgery validation failed. Required param "state" missing from persistent data.
            'persistent_data_handler' => new FBPersistentDataHandler(),
            'default_access_token' =>  session()->get('fb_access_token'),
        ]);

        $sharingPostUrl = $this->setUserFeedUrl([
            'app_id' => $FBConfig['app-id'],
            'page_link' => $request->link,
            'redirect_uri' => route('home')
        ]);

        return redirect()->to($sharingPostUrl);
    }

    /**
     * Generates URL string for feed dialog
     * 
     * More about it: https://developers.facebook.com/docs/sharing/reference/feed-dialog 
     *
     * @param array $params
     * @return string
     */
    private function setUserFeedUrl($params) {

        $url = "https://www.facebook.com/dialog/feed?app_id=%s&display=popup&link=%s&redirect_uri=%s";
        
        return sprintf($url, $params['app_id'], $params['page_link'], $params['redirect_uri']);
    }

}
