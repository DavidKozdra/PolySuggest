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
 * Mailgun mail class
 *
 * @author Shay Anderson
 */
class Mail
{
	/**
	 * Client ID
	 *
	 * @var mixed
	 */
	private $__client_id;

	/**
	 * From mail (ex: 'name@example.com')
	 *
	 * @var string
	 */
	public $from;

	/**
	 * From mail name (ex: 'Shay Anderson')
	 *
	 * @var string
	 */
	public $from_name;

	/**
	 * Mail body HTML
	 *
	 * @var string
	 */
	public $html;

	/**
	 * Mail subject
	 *
	 * @var string
	 */
	public $subject;

	/**
	 * Mail body text (non-HTML)
	 *
	 * @var string
	 */
	public $text;

	/**
	 * To mail (ex: 'name@example.com')
	 *
	 * @var string
	 */
	public $to;

	/**
	 * To mail name (ex: 'Shay Anderson')
	 *
	 * @var string
	 */
	public $to_name;

	/**
	 * Init
	 *
	 * @param mixed $client_id
	 */
	public function __construct($client_id = null)
	{
		$this->__client_id = $client_id;
	}

	/**
	 * Send message to send queue
	 *
	 * @return mixed (stdClass (JSON object) on success, false on fail)
	 */
	public function send()
	{
		return Client::sendMail($this, $this->__client_id);
	}
}