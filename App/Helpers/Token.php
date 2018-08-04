<?php

namespace App\Helpers;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Silver\Core\Env;

/**
 * token Helper
 */
class Token {
    
    /**
     * @var \Lcobucci\JWT\Token
     */
    private $token;
    
    public function generate($closureClaim = FALSE) {
        if (!is_callable($closureClaim)) {
            $closureClaim = function(Builder $builder) {
                return $builder;
            };
        }
        
        $builder = (new Builder())->setIssuer(URL)// Configures the issuer (iss claim)
                                  ->setAudience(URL)// Configures the audience (aud claim)
                                    //http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#rfc.section.4.1.7
                                  ->setId(Env::get('token_id'), TRUE)// Configures the id (jti claim), replicating as a header item
                                  ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
                                  ->setNotBefore(time() + 60)// Configures the time that the token can be used (nbf claim)
                                  ->setExpiration(time() + 3600);// Configures the expiration time of the token (exp claim)
                                    //Campos personalizados
                                    //->set('uid', 1)// Configures a new claim, called "uid"
    
        $this->token = $closureClaim($builder)
                ->sign(new Sha256(), Env::get('app_key'))// creates a signature using "testing" as key
                ->getToken();// Retrieves the generated token
    }
    
    public function check($payload) {
        $this->token = (new Parser())->parse((string)$payload); // Parses from a string
//        $token->getHeaders(); // Retrieves the token header
//        $token->getClaims(); // Retrieves the token claims
        
        return $this->verify() && $this->validate();
    }
    
    /**
     * @return \Lcobucci\JWT\Token
     */
    public function getToken() {
        if(empty($this->token)){
            $this->generate();
        }
        
        return $this->token;
    }
    
    private function verify() {
        return $this->token->verify(new Sha256(), Env::get('app_key'));
    }
    
    private function validate() {
        $validationData = new ValidationData(time() + 60);
        $validationData->setIssuer($this->token->getClaim('iss'));
        $validationData->setAudience($this->token->getClaim('aud'));
        $validationData->setId($this->token->getClaim('jti'));
    
        return $this->token->validate($validationData);
    }
    
}
