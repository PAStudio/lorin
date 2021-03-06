<?php
/**
 * GrApi.class.php
 *
 * @author Grzeogrz Struczynski <grzegorz.struczynski@getresponse.com>
 * http://getresponse.com
 */
class GetResponseIntegration {

	private $api_key;
	public $api_url = 'https://api.getresponse.com/v3';
	private $domain = null; // for GetResponse 360
	private $timeout = 8;
	public $http_status;
	public $error;

	/**
	 * Set api key and optionally API endpoint
	 * @param      $api_key
	 * @param null $api_url
	 */
	public function __construct ($api_key, $api_url = null, $domain = null) {
		$this->api_key = $api_key;

		if ( !empty($api_url)) $this->api_url = $api_url;
		if ( !empty($domain)) $this->domain = $domain;
	}

	/**
	 * We can modify internal settings
	 * @param $key
	 * @param $value
	 */
	function __set($key, $value) {
		$this->{$key} = $value;
	}

	/**
	 * @return mixed
	 */
	public function ping() {
		return $this->call('accounts');
	}

	/**
	 * Return all campaigns
	 * @return mixed
	 */
	public function getCampaigns() {
		return $this->call('campaigns');
	}

	/**
	 * get single campaign
	 * @param string $campaign_id retrieved using API
	 * @return mixed
	 */
	public function getCampaign($campaign_id) {
		return $this->call('campaigns/' . $campaign_id);
	}

	/**
	 * adding campaign
	 * @param $params
	 * @return mixed
	 */
	public function createCampaign($params) {
		return $this->call('campaigns', 'POST', $params);
	}

	/**
	 * add single contact into your campaign
	 *
	 * @param $params
	 * @return mixed
	 */
	public function addContact($params) {
		return $this->call('contacts', 'POST', $params);
	}

	/**
	 * retrieving contact by id
	 *
	 * @param string $contact_id - contact id obtained by API
	 * @return mixed
	 */
	public function getContact($contact_id) {
		return $this->call('contacts/' . $contact_id);
	}

	/**
	 * retrieving contact by params
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getContacts($params = array()) {
		return $this->call('contacts?' . $this->setParams($params));
	}

	/**
	 * updating any fields of your subscriber (without email of course)
	 * @param       $contact_id
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function updateContact($contact_id, $params = array()) {
		return $this->call('contacts/' . $contact_id, 'POST', $params);
	}

	/**
	 * drop single user by ID
	 *
	 * @param string $contact_id - obtained by API
	 * @return mixed
	 */
	public function deleteContact($contact_id) {
		return $this->call('contacts/' . $contact_id, 'DELETE');
	}

	/**
	 * retrieve account custom fields
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getCustomFields($params = array()) {
		return $this->call('custom-fields?' . $this->setParams($params));
	}

	/**
	 * retrieve single custom field
	 *
	 * @param string $cs_id obtained by API
	 * @return mixed
	 */
	public function getCustomField($custom_id) {
		return $this->call('custom-fields/' . $custom_id, 'GET');
	}

	/**
	 * retrieve single custom field
	 *
	 * @param string $cs_id obtained by API
	 * @return mixed
	 */
	public function addCustomField($params = array()) {
		return $this->call('custom-fields', 'POST', $params);
	}

	/**
	 * get single web form
	 *
	 * @param int $w_id
	 * @return mixed
	 */
	public function getWebForm($w_id) {
		return $this->call('webforms/' . $w_id);
	}

	/**
	 * retrieve all webforms
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getWebForms($params = array()) {
		return $this->call('webforms?' . $this->setParams($params));
	}


	/**
	 * get single form
	 *
	 * @param int $form_id
	 * @return mixed
	 */
	public function getForm($form_id) {
		return $this->call('forms/' . $form_id);
	}

	/**
	 * retrieve all forms
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getForms($params = array()) {
		return $this->call('forms?' . $this->setParams($params));
	}

	/**
	 * retrieve all forms
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getFormVariants($form_id) {
		return $this->call('forms/' . $form_id . '/variants');
	}

	/**
	 * Curl run request
	 *
	 * @param null $api_method
	 * @param string $http_method
	 * @param array $params
	 * @return mixed
	 * @throws Exception
	 */
	private function call($api_method = null, $http_method = 'GET', $params = array())
	{
		$certFile = getcwd() . DIRECTORY_SEPARATOR. '..' . DIRECTORY_SEPARATOR . 'cacert.pem';

		if (empty($api_method))
		{
			return (object)array(
				'httpStatus' => '400',
				'code' => '1010',
				'codeDescription' => 'Error in external resources',
				'message' => 'Invalid api method'
			);
		}

		$jparams = json_encode($params);
		$url = $this->api_url  . '/' .  $api_method;

		$headers = array('X-Auth-Token: api-key ' . $this->api_key,
			'Content-Type: application/json',
			'User-Agent: PHP GetResponse client 0 . 0 . 1',
			'X-APP-ID: 74a0976d-5d56-486a-9f1c-84081608932d'
		);

		// for GetResponse 360
		if (isset($this->domain))
		{
			$headers[] = 'X-Domain: ' . $this->domain;
		}

		//also as get method
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_ENCODING => 'gzip,deflate',
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => $this->timeout,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => $headers
		);

		if ($http_method == 'POST')
		{
			$options[CURLOPT_POST] = 1;
			$options[CURLOPT_POSTFIELDS] = $jparams;
		}
		else if ($http_method == 'DELETE')
		{
			$options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
		}

		if (file_exists($certFile))
		{
			$options[CURLOPT_CAINFO] = $certFile;
		}

		$curl = curl_init();
		curl_setopt_array($curl, $options);

		$curlExec = curl_exec($curl);

		if (false === $curlExec) {
            $this->error = curl_error($curl);
            return null;
        }

        $response = json_decode($curlExec);
        $this->http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);
		return (object)$response;
	}

	/**
	 * @param array $params
	 *
	 * @return string
	 */
	private function setParams($params = array())
	{
		$result = array();
		if (is_array($params))
		{
			foreach ($params as $key => $value)
			{
				$result[$key] = $value;
			}
		}
		return http_build_query($result);
	}

}