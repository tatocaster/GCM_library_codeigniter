GCM library For Codeigniter

----------------------------

Codeigniter library for google cloud messaging.

How To Use :

First of all, define API key constant in config file. URL is already defined


	public function sendGCM()
	{

		// load gcm library
		$this->load->library('gcm_notification');


		// prepare options array, you can use custom key s
		$opts_array = array(
		    'message'   => 'TEST',
		    'title'     => 'TEST',
		    'subtitle'  => 'TEST',
		    'tickerText'    => 'TEST',
		    'vibrate'   => 1,
		    'sound'     => 1,
		    'largeIcon' => 'large_icon',
		    'smallIcon' => 'small_icon'
		);

		// place your recipients here. select gcm_id from db or smth. don't use $key=>$value
		$reg_ids[] = '1,2,3';

		// seting recipient
		$this->gcm_notification->setRecipients($reg_ids);

		/* set Time To Live - How long (in seconds) the message should be kept on GCM storage if the device is offline.
		 Optional (default time-to-live is 4 weeks, and must be set as a JSON number).*/
		$this->gcm_notification->setTTL(20);

		// set collapse Key
		/*An arbitrary string (such as "Updates Available") that is used to collapse a group of like messages when the device is
		 offline, so that only the last message gets sent to the client. This is intended to avoid sending too many messages to
		 the phone when it comes back online.*/
		$this->gcm_notification->setCollapseKey('GCM_Library');

		// takes boolean
		/*If included, indicates that the message should not be sent immediately if the device is idle. The server will wait for
		the device to become active, and then only the last message for each collapse_key value will be sent.*/
		$this->gcm_notification->setDelay(true);

		// set predefined options
		$this->gcm_notification->setOptions($opts_array);

		// debug info. http_headers if (400,401,500) and success if 200. takes boolean
		$this->gcm_notification->setDebug(true);

		// finally send it. if DEBUG is TRUE , print , returns array
		$this->gcm_notification->send();

	}