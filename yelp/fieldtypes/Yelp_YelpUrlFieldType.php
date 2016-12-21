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
		$parts = explode('|||', $value);
		return craft()->templates->render('yelp/yelpurl/input', array(
			'name' => $name,
			'value' => $parts[0],
			'id' => array_key_exists(1, $parts) ? $parts[1] : '';
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
		$matches;
		preg_match("/^(?:https?:\/\/)?(www\.)?yelp\.com\/biz\/([^\/]+)$/i", $value, $matches);
		if (array_key_exists(2, $matches)) {
			return $matches[2];
		} else {
			// likely switched from another field type to yelp url field type. Just give back the original value.
			// should probably improve this.
			return $value;
		}
	}
}
?>
