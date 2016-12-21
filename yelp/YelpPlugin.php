<?php
namespace Craft;

class YelpPlugin extends BasePlugin
{
	function getName()
	{
		return Craft::t('Yelp');
	}

	function getVersion()
	{
		return '0.1';
	}

	function getDeveloper()
	{
		return 'STAPLEGUN';
	}

	function getDeveloperUrl()
	{
		return 'http://staplegun.us';
	}

	public function getSettingsHtml()
	{
		return craft()->templates->render('yelp/_settings', array(
			'settings' => $this->getSettings()
		));
	}

	protected function defineSettings()
	{
		return array(
			'oAuthToken' => array(AttributeType::String, 'label' => 'Yelp OAuth Token'),
			'oAuthTokenSecret' => array(AttributeType::String, 'label' => 'Yelp OAuth Token Secret'),
			'oAuthConsumerKey' => array(AttributeType::String, 'label' => 'Yelp OAuth Consumer Key'),
			'oAuthConsumerSecret' => array(AttributeType::String, 'label' => 'Yelp OAuth Token Consumer Secret'),
			'cacheDuration' => array(AttributeType::Number, 'label' => 'Cache Duration (seconds)', 'default' => 3600)
		);
	}
}
