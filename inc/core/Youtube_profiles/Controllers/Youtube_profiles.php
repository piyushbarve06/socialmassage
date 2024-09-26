<?php
namespace Core\Youtube_profiles\Controllers;
use Exception;
class Youtube_profiles extends \CodeIgniter\Controller
{
    public function __construct(){
        $reflect = new \ReflectionClass(get_called_class());
        $this->module = strtolower( $reflect->getShortName() );
        $this->config = include realpath( __DIR__."/../Config.php" );

        $client_id = get_option('youtube_client_id', '');
        $client_secret = get_option('youtube_api_secret', '');
        $api_key = get_option('youtube_api_key', '');

        if($client_id == "" || $client_secret == "" || $api_key == ""){
            redirect_to( base_url("social_network_settings/index/".$this->config['parent']['id']) ); 
        }

        $this->client = new \Google\Client();
        $this->client->setAccessType("offline");
        $this->client->setApprovalPrompt("force");
        $this->client->setApplicationName("Youtube");
        $this->client->setClientId( $client_id );
        $this->client->setClientSecret( $client_secret );
        $this->client->setRedirectUri(get_module_url());
        $this->client->setDeveloperKey( $api_key );
        $this->client->setScopes(
            [
                'https://www.googleapis.com/auth/youtube', 
                'https://www.googleapis.com/auth/userinfo.email'
            ]
        );

        $this->youtube = new \Google\Service\YouTube($this->client);

		
    }
    
    public function index() {

        try {
            if( !get_session("YT_AccessToken") ){
					function exchangeAuthCodeForToken($authCode) {
						$url = 'https://oauth2.googleapis.com/token';
						$clientId = '152798993005-m6sek5cof7rh2r7tb6m27hqujid4jrc4.apps.googleusercontent.com';
						$clientSecret = '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';
						$redirectUri = 'https://app2.postglance.com/youtube_profiles'; // Must match the redirect URI set in your app
						$postData = [
							'code' => $authCode,
							'client_id' => $clientId,
							'client_secret' => $clientSecret,
							'redirect_uri' => $redirectUri,
							'grant_type' => 'authorization_code',
						];
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, [
							'Content-Type: application/x-www-form-urlencoded'
						]);
						$response = curl_exec($ch);
						if (curl_errno($ch)) {
							throw new Exception('Error: ' . curl_error($ch));
						}
						curl_close($ch);
						$data = json_decode($response, true);
						if (isset($data['access_token'])) {
							return $data['access_token'];
						} else {
							throw new Exception('Failed to get access token: ' . $response);
						}
					}
					$authCode = post("code"); // Extract 'code' from your URL
					try {
						$accessToken = exchangeAuthCodeForToken($authCode);
						//echo 'Access Token: ' . $accessToken;
					} catch (Exception $e) {
						echo 'Error: ' . $e->getMessage();
					}
				//$accessToken = $this->client->getAccessToken();
                set_session(["YT_AccessToken" => json_encode($accessToken)]);
				
            }else{
                $accessToken = json_decode( get_session("YT_AccessToken") , true);
            }
            
            //$this->client->setAccessToken($accessToken);

            $part = 'brandingSettings,status,id,snippet,contentDetails,contentOwnerDetails,statistics';
			
            $optionalParams = array(
                'mine' => true
            );
			// echo $accessToken;
            //$response = $this->youtube->channels->listChannels($part, $optionalParams);
			
			$apiUrl = "https://www.googleapis.com/youtube/v3/channels";
			$params = [
				'part' => 'brandingSettings,status,id,snippet,contentDetails,contentOwnerDetails,statistics',
				'mine' => 'true'  
			];
			$apiUrl .= '?' . http_build_query($params);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $apiUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Authorization: Bearer ' . $accessToken, 
				'Accept: application/json'
			]);
			$response = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error: ' . curl_error($ch);
			} else {
				$response = json_decode($response, true);
				 set_session(["YT_responce" => $response]);
			}
			curl_close($ch);
		    //print_r($response);
			if (!is_string($response)) {
				if (is_array($response) && isset($response['items']) && !empty($response['items'])) {
					$result = [];  // Initialize $result array

					foreach ($response['items'] as $key => $row) {
						$result[] = (object)[
							'id' => $row['id'],
							'name' => $row['snippet']['title'],
							'avatar' => isset($row['snippet']['thumbnails']['default']['url']) ? $row['snippet']['thumbnails']['default']['url'] : '', 
							//'desc' => $row['snippet']['localized']['description'] ?? '', // Uncomment if needed
						];
					}

					$profiles = [
						"status" => "success",
						"config" => $this->config,
						"result" => $result
					];
				} else {
					$profiles = [
						"status" => "error",
						"config" => $this->config,
						"message" => __('No profile to add')
					];
				}
			} else {
				$profiles = [
					"status" => "error",
					"config" => $this->config,
					"message" => $response  // Response is a string, so it's likely an error message
				];
			}

        } catch (\Exception $e) {

            $message = json_decode($e->getMessage());

            if($message){
                $message = $message->error->message;
            }else{
                $message = $e->getMessage();
            }

            $profiles = [
                "status" => "error",
                "config" => $this->config,
                "message" => $message
            ];
        }

        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            "content" => view('Core\Youtube_profiles\Views\add', $profiles)
        ];

        return view('Core\Youtube_profiles\Views\index', $data);
    }

    public function oauth(){
        remove_session(["YT_AccessToken"]);
        remove_session(["YT_responce"]);
        $oauth_link = $this->client->createAuthUrl();
        redirect_to($oauth_link);
    }

    public function save()
    {
        $ids = post('id');
        $team_id = get_team("id");
        $accessToken = json_decode( get_session("YT_AccessToken") , true);

        validate('empty', __('Please select a profile to add'), $ids);
        $response = get_session("YT_responce");

        if(!is_string($response)){

            if (is_array($response) && isset($response['items']) && !empty($response['items'])) 
            {
                foreach ($response['items'] as $key => $row)
                {
                    if(in_array($row['id'], $ids, true)){
                        $item = db_get('*', TB_ACCOUNTS, "social_network = 'youtube' AND team_id = '{$team_id}' AND pid = '".$row['id']."'");
                        if(!$item){
                            //Check limit number 
                            check_number_account("youtube", "channel");
                            $avatar = save_img( isset($row['snippet']['thumbnails']['default']['url']) ? $row['snippet']['thumbnails']['default']['url'] : '', WRITEPATH.'avatar/' );
                            $data = [
                                'ids' => ids(),
                                'module' => $this->module,
                                'social_network' => 'youtube',
                                'category' => 'channel',
                                'login_type' => 1,
                                'can_post' => 1,
                                'team_id' => $team_id,
                                'pid' => $row['id'],
                                'name' => $row['snippet']['title'],
                                'username' => $row['snippet']['title'],
                                'token' => json_encode( $accessToken ),
                                'avatar' => $avatar,
                                'url' => 'https://www.youtube.com/channel/'.$row['id'],
                                'data' => NULL,
                                'status' => 1,
                                'changed' => time(),
                                'created' => time()
                            ];

                            db_insert(TB_ACCOUNTS, $data);
							remove_session(["YT_AccessToken"]);
        					remove_session(["YT_responce"]);
                        }else{
                            unlink( get_file_path($item->avatar) );
                            $avatar = save_img( isset($row['snippet']['thumbnails']['default']['url']) ? $row['snippet']['thumbnails']['default']['url'] : '', WRITEPATH.'avatar/' );
                            $data = [
                                'can_post' => 1,
                                'pid' => $row['id'],
                                'name' => $row['snippet']['title'],
                                'username' => $row['snippet']['title'],
                                'token' => json_encode( $accessToken ),
                                'avatar' => $avatar,
                                'url' => 'https://www.youtube.com/channel/'.$row['id'],
                                'status' => 1,
                                'changed' => time(),
                            ];

                            db_update(TB_ACCOUNTS, $data, ['id' => $item->id]);
							remove_session(["YT_AccessToken"]);
        					remove_session(["YT_responce"]);
                        }
                    }
                }

                ms([
                    "status" => "success",
                    "message" => __("Success")
                ]);
            }else{
                ms([
                    "status" => "error",
                    "message" => __('No profile to add')
                ]);
            }
   
        }else{
            ms([
                "status" => "error",
                "message" => $response
            ]);
        }
    }
}