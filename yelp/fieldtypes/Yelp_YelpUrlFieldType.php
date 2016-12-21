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
			'value' => is_array($value) ? $value['url']: $value,
			'id' => is_array($value) ? $value['id'] : ''
		));
	}

	public function prepValueFromPost($value)
	{
		if ($value) {
			$matches;
			preg_match("/^(?:https?:\/\/)?(www\.)?yelp\.com\/biz\/([^\/]+)$/i", $value, $matches);
			return $value . '|||' . $matches[2];
		}
		return;
	}

	public function prepValue($value)
	{
		if ($value) {
			$parts = explode('|||', $value);
			if (is_array($parts)) {
				return array('id' => $parts[1], 'url' => $parts[0]);
			} else {
				return $value;
			}
		}
		return;
	}
}
?>
