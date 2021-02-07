<?php
/**
 * Mail sender for Mailgun API for PHP 5.5+
 *
 * @package Mailgun
 * @version 0.0.1
 * @copyright 2015 Shay Anderson <http://www.shayanderson.com>
 * @license MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @link <http://www.shayanderson.com/php/mailgun-client-and-mail-classes-for-php.htm>
 */
namespace Mailgun;

/**
 * Mailgun client class - requires PHP curl functions
 *
 * @author Shay Anderson
 */
class Client
{
	/**
	 * Clients
	 *
	 * @var array
	 */
	private static $__clients = [];

	/**
	 * Current client ID
	 *
	 * @var int
	 */
	private static $__id = 0;

	/**
	 * Debug mode flag
	 *
	 * @var boolean
	 */
	public static $debug = false;

	/**
	 * Connection (cURL) timeout in seconds)
	 *
	 * @var int
	 */
	public static $timeout = 10;

	/**
	 * API URL (must include '{$domain}', ex: 'https://api.mailgun.net/v3/{$domain}/messages')
	 *
	 * @var string
	 */
	public static $url = 'https://api.mailgun.net/v3/{$domain}/messages';

	/**
	 * Trigger error (when debugging)
	 *
	 * @param string $message
	 * @return void
	 */
	private static function __error($message)
	{
		if(self::$debug)
		{
			trigger_error($message, E_USER_ERROR);
		}
	}

	/**
	 * Add client
	 *
	 * @param string $domain
	 * @param string $key
	 * @param mixed $id (optional)
	 * @return void
	 */
	public static function add($domain, $key, $id = null)
	{
		self::$__clients[ $id !== null ? $id : ++self::$__id ] = (object)[
			'domain' => $domain,
			'key' => $key
		];
	}

	/**
	 * Client object getter
	 *
	 * @param mixed $id
	 * @return mixed (stdObject, or null on no client found)
	 */
	public static function get($id = null)
	{
		if($id === null) // default
		{
			return current(self::$__clients);
		}
		else if(isset(self::$__clients[$id]))
		{
			return self::$__clients[$id];
		}

		return null; // invalid client data
	}

	/**
	 * Send mail
	 *
	 * @param \Mailgun\Mail $mail
	 * @param mixed $client_id (optional)
	 * @return boolean (false on fail)
	 */
	public static function sendMail(\Mailgun\Mail &$mail, $client_id)
	{
		$client = self::get($client_id);

		if($client === null) // validate client
		{
			self::__error("Invalid client ID '{$client_id}', client not found");
			return false;
		}

		if(empty($client->domain) || empty($client->key)) // validate client data
		{
			self::__error('Empty client domain and/or key');
			return false;
		}

		$data = [
			'from' => !empty($mail->from_name)
				? "{$mail->from_name} <{$mail->from}>"
				: $mail->from,
			'to' => !empty($mail->to_name)
				? "{$mail->to_name} <{$mail->to}>"
				: $mail->to,
			'subject' => $mail->subject,
		];

		if(!empty($mail->html))
		{
			$data['html'] = $mail->html;
		}
		else if(!empty($mail->text))
		{
			$data['text'] = $mail->text;
		}
		else
		{
			self::__error('Empty mail html and text');
			return false;
		}

		// set URL
		$ch = curl_init(str_replace('{$domain}', $client->domain, self::$url));

		// set curl options
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "api:{$client->key}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int)self::$timeout);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$res = curl_exec($ch);

		$err = null;
		if($res === false)
		{
			$err = 'Curl error: ' . curl_error($ch);
		}
		else if($res == 'Forbidden')
		{
			$err = 'Connection is forbidden, check domain and key';
		}

		curl_close($ch);

		if($err !== null)
		{
			self::__error($err);
			return false;
		}

		return json_decode($res);
	}
}