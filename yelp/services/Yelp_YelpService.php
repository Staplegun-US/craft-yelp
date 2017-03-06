<?php
namespace Craft;


class Yelp_YelpService extends BaseApplicationComponent
{
	protected $oAuthToken;
	protected $oAuthTokenSecret;
	protected $oAuthConsumerKey;
	protected $oAuthConsumerSecret;
	public $cacheDuration;
	public $apiHost;

	public function init()
	{
		require_once(CRAFT_PLUGINS_PATH . 'yelp/lib/YelpOAuth.php');
		$settings = craft()->plugins->getPlugin('yelp')->getSettings();

		$this->cacheDuration = $settings->cacheDuration;
		$this->oAuthToken = $settings->oAuthToken;
		$this->oAuthTokenSecret = $settings->oAuthTokenSecret;
		$this->oAuthConsumerKey = $settings->oAuthConsumerKey;
		$this->oAuthConsumerSecret = $settings->oAuthConsumerSecret;

		$this->apiHost = 'api.yelp.com';
	}

	protected function _makeRequest($path)
	{
		$unsigned_url = 'https://' . $this->apiHost . $path;
		try {
			$cached = craft()->cache->get($unsigned_url);
			if ($cached) {
				return $cached;
			} else {
				$token = new \OAuthToken($this->oAuthToken, $this->oAuthTokenSecret);
				$consumer = new \OAuthConsumer($this->oAuthConsumerKey, $this->oAuthConsumerSecret);
				$signature_method = new \OAuthSignatureMethod_HMAC_SHA1();
				$oauth_request = \OAuthRequest::from_consumer_and_token(
					$consumer,
					$token,
					'GET',
					$unsigned_url
				);
				$oauth_request->sign_request($signature_method, $consumer, $token);
				$signed_url = $oauth_request->to_url();

				$client = new \Guzzle\Http\Client();
				$request = $client->get($signed_url);
				// $request->addHeader('Authorization', 'Bearer ' . $this->oAuthToken);
				$response = $request->send();
				if (!$response->issuccessful()){
					return;
				}
				$output = $response->json();
				craft()->cache->set($unsigned_url, $output, $this->cacheDuration);
				return $output;
			}
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
			return;
		} catch(\Exception $e) {
			throw $e;
		}
	}
	
	public function getSearch($options)
	{
		$path = '/v2/search/?' . http_build_query($options);
		$response = $this->_makeRequest($path);
		return $response;
	}

	public function getBusiness($id, $options)
	{
		$path = '/v2/business/' . $id . '?' . http_build_query($options);
		$response = $this->_makeRequest($path);
		return $response;
	}

	public function getPhoneSearch($phone, $options)
	{
		$path = '/v2/phone_search/?phone=' . $phone . '&' . http_build_query($options);
		$response = $this->_makeRequest($path);
		return $response;
	}
}
