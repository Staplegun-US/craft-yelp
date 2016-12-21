<?php
namespace Craft;

class YelpVariable
{
	public function search($options)
	{
		return craft()->yelp_yelp->getSearch($options);
	}

	public function business($id, $options = array())
	{
		return craft()->yelp_yelp->getBusiness($id, $options);
	}

	public function phone_search($phone, $options = array())
	{
		return craft()->yelp_yelp->getPhoneSearch($phone, $options);
	}
}
