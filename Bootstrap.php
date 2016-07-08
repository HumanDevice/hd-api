<?php

namespace hd\yii2\oauth2server;

class Bootstrap implements \yii\base\BootstrapInterface
{
    /**
     * @var array Model's map
     */
    private $_modelMap = [
        'OauthClients'               => 'hd\yii2\oauth2server\models\OauthClients',
        'OauthAccessTokens'          => 'hd\yii2\oauth2server\models\OauthAccessTokens',
        'OauthAuthorizationCodes'    => 'hd\yii2\oauth2server\models\OauthAuthorizationCodes',
        'OauthRefreshTokens'         => 'hd\yii2\oauth2server\models\OauthRefreshTokens',
        'OauthScopes'                => 'hd\yii2\oauth2server\models\OauthScopes',
    ];
    
    /**
     * @var array Storage's map
     */
    private $_storageMap = [
        'access_token'          => 'hd\yii2\oauth2server\storage\Pdo',
        'authorization_code'    => 'hd\yii2\oauth2server\storage\Pdo',
        'client_credentials'    => 'hd\yii2\oauth2server\storage\Pdo',
        'client'                => 'hd\yii2\oauth2server\storage\Pdo',
        'refresh_token'         => 'hd\yii2\oauth2server\storage\Pdo',
        'user_credentials'      => 'hd\yii2\oauth2server\storage\Pdo',
        'public_key'            => 'hd\yii2\oauth2server\storage\Pdo',
        'jwt_bearer'            => 'hd\yii2\oauth2server\storage\Pdo',
        'scope'                 => 'hd\yii2\oauth2server\storage\Pdo',
    ];
    
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('oauth2') && ($module = $app->getModule('oauth2')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                \Yii::$container->set("filsh\\yii2\\oauth2server\\models\\" . $name, $definition);
                $module->modelMap[$name] = is_array($definition) ? $definition['class'] : $definition;
            }
            
            $this->_storageMap = array_merge($this->_storageMap, $module->storageMap);
            foreach ($this->_storageMap as $name => $definition) {
                \Yii::$container->set($name, $definition);
                $module->storageMap[$name] = is_array($definition) ? $definition['class'] : $definition;
            }
            
            if ($app instanceof \yii\console\Application) {
                $module->controllerNamespace = 'hd\yii2\oauth2server\commands';
            }
        }
    }
}