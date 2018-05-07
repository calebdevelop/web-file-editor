<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 6:18 AM
 */

namespace TSK\WebFileEditorBundle\Util;


class GoogleDriveConfig
{
    private static $googleClientInstance = null;

    private static $authCode = null;

    public static $root_dir = null;

    public static $allscope = [
        \Google_Service_Drive::DRIVE,
        \Google_Service_Drive::DRIVE_APPDATA,
        \Google_Service_Drive::DRIVE_FILE,
        \Google_Service_Drive::DRIVE_METADATA,
        \Google_Service_Drive::DRIVE_METADATA_READONLY,
        \Google_Service_Drive::DRIVE_PHOTOS_READONLY,
        \Google_Service_Drive::DRIVE_READONLY,
        \Google_Service_Drive::DRIVE_SCRIPTS,
        \Google_Service_Drive::DRIVE,
    ];


    //override self::$scope
    public static function setScope(array $scope){
        self::$scope = $scope;
    }

    public static function setAuthCode($code){
        self::$authCode = $code;
    }

    private function __construct()
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Drive API Quickstart');
        $client->setScopes(self::$scope);
        $client->setAuthConfig(__DIR__.'/../config/client_secret.json');
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandConfigDirectory('credentials.json');
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            if (is_null(self::$authCode)){
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                header('Location: '.$authUrl);
            }
            $authCode = self::$authCode;

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        self::$googleClientInstance = $client;

    }

    public static function getInstance() : \Google_Client
    {
        if(is_null(self::$googleClientInstance) || self::$googleClientInstance->isAccessTokenExpired()){
            new GoogleDriveConfig();
        }
        return self::$googleClientInstance;
    }

    private function expandConfigDirectory($path)
    {
        return realpath(__DIR__).'/../config/'.$path;
    }
}