<?php
namespace Craft;

class Yelp_YelpUrlFieldType extends BaseFieldType
{
	public function getName()
	{
		return Craft::t('Yelp URL');
	}

	public function getInputHtml($name, $value)
	{
		return craft()->templates->render('yelp/yelpurl/input', array(
			'name' => $name,
			'value' => is_array($value) ? $value[0]: $value,
			'id' => is_array($value) ? $value[1] : ''
		));
	}

	public function prepValueFromPost($value)
	{
		$matches;
		preg_match("/^(?:https?:\/\/)?(www\.)?yelp\.com\/biz\/([^\/]+)$/i", $value, $matches);
		return $value . '|||' . $matches[2];
	}

	public function prepValue($value)
	{
		$parts = explode('|||', $value);
		if (is_array($parts)) {
			return $parts[1];
		} else {
			return $value;
		}
	}
}
?>
