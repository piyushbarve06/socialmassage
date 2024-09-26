<?php
session_start();

require_once 'vendor/autoload.php';

$provider = new Smolblog\OAuth2\Client\Provider\Twitter([
	'clientId'          => 'WHlDRFY5Z09QUXpONm93TEgyeWI6MTpjaQ',
	'clientSecret'      => 'eHCgfOUvTQ6KV2TQwtjY2nT7Oz4WqV6EWRFIQ8bfrYbH8d4FaD',
	'redirectUri'       => 'https://app2.postglance.com/twitter.php',
]);

if (!isset($_GET['code'])) {
	unset($_SESSION['oauth2state']);
	unset($_SESSION['oauth2verifier']);
	
	// Optional: The default scopes are ‘tweet.read’, ‘users.read’,
	// and ‘offline.access’. You can change them like this:
	$options = [
		"scope" => [
			"tweet.read",
			"tweet.write",
			"tweet.moderate.write",
			"users.read",
			"follows.read",
			"follows.write",
			"offline.access",
			"space.read",
			"mute.read",
			"mute.write",
			"like.read",
			"like.write",
			"list.read",
			"list.write",
			"block.read",
			"block.write",
			"bookmark.read",
			"bookmark.write",
		],
	]; 
		

	// If we don't have an authorization code then get one
	$authUrl = $provider->getAuthorizationUrl($options);
	$_SESSION['oauth2state'] = $provider->getState();

	// We also need to store the PKCE Verification code so we can send it with
	// the authorization code request.
	$_SESSION['oauth2verifier'] = $provider->getPkceVerifier();

	header('Location: '.$authUrl);
	exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif ($_GET['state'] !== $_SESSION['oauth2state']) {

	unset($_SESSION['oauth2state']);
	exit('Invalid state');

} else {

	try {

		// Try to get an access token (using the authorization code grant)
		$token = $provider->getAccessToken('authorization_code', [
			'code' => $_GET['code'],
			'code_verifier' => $_SESSION['oauth2verifier'],
		]);

		// Optional: Now you have a token you can look up a users profile data
		// We got an access token, let's now get the user's details
		$user = $provider->getResourceOwner($token);

		// Use these details to create a new profile
		printf('Hello %s!', $user->getName());

	} catch (Exception $e) {
		echo '<pre>';
		print_r($e);
		echo '</pre>';

		// Failed to get user details
		exit('Oh dear...');
	}

	// Use this to interact with an API on the users behalf
	echo $token->getToken();
}