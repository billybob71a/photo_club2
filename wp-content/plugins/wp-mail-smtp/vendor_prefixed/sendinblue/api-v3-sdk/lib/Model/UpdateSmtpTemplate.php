<?php

/**
 * UpdateSmtpTemplate
 *
 * PHP version 5
 *
 * @category Class
 * @package  SendinBlue\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
/**
 * SendinBlue API
 *
 * SendinBlue provide a RESTFul API that can be used with any languages. With this API, you will be able to :   - Manage your campaigns and get the statistics   - Manage your contacts   - Send transactional Emails and SMS   - and much more...  You can download our wrappers at https://github.com/orgs/sendinblue  **Possible responses**   | Code | Message |   | :-------------: | ------------- |   | 200  | OK. Successful Request  |   | 201  | OK. Successful Creation |   | 202  | OK. Request accepted |   | 204  | OK. Successful Update/Deletion  |   | 400  | Error. Bad Request  |   | 401  | Error. Authentication Needed  |   | 402  | Error. Not enough credit, plan upgrade needed  |   | 403  | Error. Permission denied  |   | 404  | Error. Object does not exist |   | 405  | Error. Method not allowed  |   | 406  | Error. Not Acceptable  |
 *
 * OpenAPI spec version: 3.0.0
 * Contact: contact@sendinblue.com
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 2.4.12
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */
namespace WPMailSMTP\Vendor\SendinBlue\Client\Model;

use ArrayAccess;
use WPMailSMTP\Vendor\SendinBlue\Client\ObjectSerializer;
/**
 * UpdateSmtpTemplate Class Doc Comment
 *
 * @category Class
 * @package  SendinBlue\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class UpdateSmtpTemplate implements \WPMailSMTP\Vendor\SendinBlue\Client\Model\ModelInterface, \ArrayAccess
{
    const DISCRIMINATOR = null;
    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'updateSmtpTemplate';
    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = ['tag' => 'string', 'sender' => 'WPMailSMTP\\Vendor\\SendinBlue\\Client\\Model\\UpdateSmtpTemplateSender', 'templateName' => 'string', 'htmlContent' => 'string', 'htmlUrl' => 'string', 'subject' => 'string', 'replyTo' => 'string', 'toField' => 'string', 'attachmentUrl' => 'string', 'isActive' => 'bool'];
    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = ['tag' => null, 'sender' => null, 'templateName' => null, 'htmlContent' => null, 'htmlUrl' => 'url', 'subject' => null, 'replyTo' => 'email', 'toField' => null, 'attachmentUrl' => 'url', 'isActive' => null];
    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }
    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }
    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = ['tag' => 'tag', 'sender' => 'sender', 'templateName' => 'templateName', 'htmlContent' => 'htmlContent', 'htmlUrl' => 'htmlUrl', 'subject' => 'subject', 'replyTo' => 'replyTo', 'toField' => 'toField', 'attachmentUrl' => 'attachmentUrl', 'isActive' => 'isActive'];
    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = ['tag' => 'setTag', 'sender' => 'setSender', 'templateName' => 'setTemplateName', 'htmlContent' => 'setHtmlContent', 'htmlUrl' => 'setHtmlUrl', 'subject' => 'setSubject', 'replyTo' => 'setReplyTo', 'toField' => 'setToField', 'attachmentUrl' => 'setAttachmentUrl', 'isActive' => 'setIsActive'];
    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = ['tag' => 'getTag', 'sender' => 'getSender', 'templateName' => 'getTemplateName', 'htmlContent' => 'getHtmlContent', 'htmlUrl' => 'getHtmlUrl', 'subject' => 'getSubject', 'replyTo' => 'getReplyTo', 'toField' => 'getToField', 'attachmentUrl' => 'getAttachmentUrl', 'isActive' => 'getIsActive'];
    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }
    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }
    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }
    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }
    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];
    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['tag'] = isset($data['tag']) ? $data['tag'] : null;
        $this->container['sender'] = isset($data['sender']) ? $data['sender'] : null;
        $this->container['templateName'] = isset($data['templateName']) ? $data['templateName'] : null;
        $this->container['htmlContent'] = isset($data['htmlContent']) ? $data['htmlContent'] : null;
        $this->container['htmlUrl'] = isset($data['htmlUrl']) ? $data['htmlUrl'] : null;
        $this->container['subject'] = isset($data['subject']) ? $data['subject'] : null;
        $this->container['replyTo'] = isset($data['replyTo']) ? $data['replyTo'] : null;
        $this->container['toField'] = isset($data['toField']) ? $data['toField'] : null;
        $this->container['attachmentUrl'] = isset($data['attachmentUrl']) ? $data['attachmentUrl'] : null;
        $this->container['isActive'] = isset($data['isActive']) ? $data['isActive'] : null;
    }
    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        return $invalidProperties;
    }
    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return \count($this->listInvalidProperties()) === 0;
    }
    /**
     * Gets tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->container['tag'];
    }
    /**
     * Sets tag
     *
     * @param string $tag Tag of the template
     *
     * @return $this
     */
    public function setTag($tag)
    {
        $this->container['tag'] = $tag;
        return $this;
    }
    /**
     * Gets sender
     *
     * @return \SendinBlue\Client\Model\UpdateSmtpTemplateSender
     */
    public function getSender()
    {
        return $this->container['sender'];
    }
    /**
     * Sets sender
     *
     * @param \SendinBlue\Client\Model\UpdateSmtpTemplateSender $sender sender
     *
     * @return $this
     */
    public function setSender($sender)
    {
        $this->container['sender'] = $sender;
        return $this;
    }
    /**
     * Gets templateName
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->container['templateName'];
    }
    /**
     * Sets templateName
     *
     * @param string $templateName Name of the template
     *
     * @return $this
     */
    public function setTemplateName($templateName)
    {
        $this->container['templateName'] = $templateName;
        return $this;
    }
    /**
     * Gets htmlContent
     *
     * @return string
     */
    public function getHtmlContent()
    {
        return $this->container['htmlContent'];
    }
    /**
     * Sets htmlContent
     *
     * @param string $htmlContent Required if htmlUrl is empty. Body of the message (HTML must have more than 10 characters)
     *
     * @return $this
     */
    public function setHtmlContent($htmlContent)
    {
        $this->container['htmlContent'] = $htmlContent;
        return $this;
    }
    /**
     * Gets htmlUrl
     *
     * @return string
     */
    public function getHtmlUrl()
    {
        return $this->container['htmlUrl'];
    }
    /**
     * Sets htmlUrl
     *
     * @param string $htmlUrl Required if htmlContent is empty. URL to the body of the email (HTML)
     *
     * @return $this
     */
    public function setHtmlUrl($htmlUrl)
    {
        $this->container['htmlUrl'] = $htmlUrl;
        return $this;
    }
    /**
     * Gets subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->container['subject'];
    }
    /**
     * Sets subject
     *
     * @param string $subject Subject of the email
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->container['subject'] = $subject;
        return $this;
    }
    /**
     * Gets replyTo
     *
     * @return string
     */
    public function getReplyTo()
    {
        return $this->container['replyTo'];
    }
    /**
     * Sets replyTo
     *
     * @param string $replyTo Email on which campaign recipients will be able to reply to
     *
     * @return $this
     */
    public function setReplyTo($replyTo)
    {
        $this->container['replyTo'] = $replyTo;
        return $this;
    }
    /**
     * Gets toField
     *
     * @return string
     */
    public function getToField()
    {
        return $this->container['toField'];
    }
    /**
     * Sets toField
     *
     * @param string $toField To personalize the «To» Field. If you want to include the first name and last name of your recipient, add {FNAME} {LNAME}. These contact attributes must already exist in your SendinBlue account. If input parameter 'params' used please use {{contact.FNAME}} {{contact.LNAME}} for personalization
     *
     * @return $this
     */
    public function setToField($toField)
    {
        $this->container['toField'] = $toField;
        return $this;
    }
    /**
     * Gets attachmentUrl
     *
     * @return string
     */
    public function getAttachmentUrl()
    {
        return $this->container['attachmentUrl'];
    }
    /**
     * Sets attachmentUrl
     *
     * @param string $attachmentUrl Absolute url of the attachment (no local file). Extension allowed: xlsx, xls, ods, docx, docm, doc, csv, pdf, txt, gif, jpg, jpeg, png, tif, tiff, rtf, bmp, cgm, css, shtml, html, htm, zip, xml, ppt, pptx, tar, ez, ics, mobi, msg, pub and eps
     *
     * @return $this
     */
    public function setAttachmentUrl($attachmentUrl)
    {
        $this->container['attachmentUrl'] = $attachmentUrl;
        return $this;
    }
    /**
     * Gets isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->container['isActive'];
    }
    /**
     * Sets isActive
     *
     * @param bool $isActive Status of the template. isActive = false means template is inactive, isActive = true means template is active
     *
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->container['isActive'] = $isActive;
        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }
    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (\is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }
    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (\defined('JSON_PRETTY_PRINT')) {
            // use JSON pretty print
            return \json_encode(\WPMailSMTP\Vendor\SendinBlue\Client\ObjectSerializer::sanitizeForSerialization($this), \JSON_PRETTY_PRINT);
        }
        return \json_encode(\WPMailSMTP\Vendor\SendinBlue\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}
