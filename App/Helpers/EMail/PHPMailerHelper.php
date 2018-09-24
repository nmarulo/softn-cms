<?php

namespace App\Helpers\EMail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Silver\Core\Env;

/**
 * Class EMailerHelper
 * @author Nicolás Marulanda P.
 */
class PHPMailerHelper {
    
    private const KEY_HTML_TEMPLATE = 'html_template';
    
    private const KEY_TEXT_TEMPLATE = 'text_template';
    
    /**
     * @var PHPMailer
     */
    private $phpMailer;
    
    private $templates;
    
    public function __construct() {
        $this->templates = [];
        $this->phpMailer = new PHPMailer(TRUE);
    }
    
    /**
     * @return $this
     * @throws Exception
     */
    public function setUpSMTP() {
        $this->phpMailer->isSMTP(); // Set mailer to use SMTP
        $this->phpMailer->SMTPDebug  = Env::get('php_mailer.smtp_debug');
        $this->phpMailer->Host       = Env::get('php_mailer.host');
        $this->phpMailer->SMTPAuth   = Env::get('php_mailer.smtp_auth');
        $this->phpMailer->Username   = Env::get('php_mailer.username');
        $this->phpMailer->Password   = Env::get('php_mailer.password');
        $this->phpMailer->SMTPSecure = Env::get('php_mailer.smtp_secure');
        $this->phpMailer->Port       = Env::get('php_mailer.port');
        $this->phpMailer->setFrom(Env::get('php_mailer.from_address'), Env::get('php_mailer.from_name'));
        
        return $this;
    }
    
    public function setHtmlTemplate($template, $values) {
        $this->templates[self::KEY_HTML_TEMPLATE] = $this->getContent($template, $values);
    }
    
    private function getContent($template, $values) {
        $content = $template;
        
        foreach ($values as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
        
        return $content;
    }
    
    public function setTextTemplate($template, $values) {
        $this->templates[self::KEY_TEXT_TEMPLATE] = $this->getContent($template, $values);
    }
    
    public function addAddress($address, $name = '') {
        $this->phpMailer->addAddress($address, $name);
    }
    
    /**
     * @param string $path
     * @param string $name
     * @param string $encoding
     * @param string $type
     * @param string $disposition
     *
     * @throws Exception
     */
    public function addAttachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment') {
        $this->phpMailer->addAttachment($path, $name, $encoding, $type, $disposition);
    }
    
    public function addStringAttachment($string, $filename, $encoding = 'base64', $type = '', $disposition = 'attachment') {
        $this->phpMailer->addStringAttachment(file_get_contents($string), $filename, $encoding, $type, $disposition);
    }
    
    public function addEmbeddedImage($path, $cid, $name = '', $encoding = 'base64', $type = '', $disposition = 'inline') {
        $this->phpMailer->addEmbeddedImage($path, $cid, $name, $encoding, $type, $disposition);
    }
    
    public function addStringEmbeddedImage($string, $cid, $name = '', $encoding = 'base64', $type = '', $disposition = 'inline') {
        $this->phpMailer->addStringEmbeddedImage(file_get_contents($string), $cid, $name, $encoding, $type, $disposition);
    }
    
    /**
     * @param string $subject
     *
     * @throws Exception
     */
    public function send($subject) {
        if (Env::get('php_mailer.activated')) {
            $this->phpMailer->Subject = $subject;
            $this->phpMailer->msgHTML($this->templates[self::KEY_HTML_TEMPLATE], ROOT);
            $this->phpMailer->send();
        }
    }
}
