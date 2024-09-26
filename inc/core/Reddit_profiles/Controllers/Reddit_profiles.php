<?php
namespace Core\Reddit_profiles\Controllers;

class Reddit_profiles extends \CodeIgniter\Controller
{
	public function __construct(){
		$reflect = new \ReflectionClass(get_called_class());
        $this->module = strtolower( $reflect->getShortName() );
        $this->config = include realpath( __DIR__."/../Config.php" );
		 
        $client_id = get_option("reddit_client_id", "");
        $client_secret = get_option("reddit_client_secret", "");
		
        include get_module_dir( __DIR__ , 'Libraries/Redditoauth.php');
		
		$this->module_name = 'Reddit_profiles';
		$this->module_icon = 'fab fa-reddit';
		$this->module_color = '#4267b2';

        if($client_id == "" || $client_secret == ""){
            redirect( get_url("social_network_configuration/index/reddit") );
        }

        $this->reddit = new \Redditoauth();
	}

	public function index($page = "", $ids = "")
	{
		//
        try {
            //get_session("readdit_access_token");
			if(!get_session("readdit_access_token")){
                $response = $this->reddit->getAccessToken( post('code') );
                var_dump($response );
                if( isset($response->access_token) ){
                    $access_token = json_encode($response);
                    //set_session("readdit_access_token", $access_token);
					set_session(["readdit_access_token" => $access_token]);
					//var_dump($access_token);
                }else{
                    $access_token = false;
                    $data = $response;
                }
            }else{
                $access_token = get_session("readdit_access_token");
				//var_dump($access_token);
            }
				
            if($access_token){
                $this->reddit->setAccessToken($access_token);
                $profile = $this->reddit->getUser();

                $result = [];
                $result[] = (object)[
                    'id' => $profile->subreddit->display_name,
                    'name' => $profile->name,
                    'avatar' => $profile->icon_img,
                    'desc' => $profile->name
                ];
				var_dump($result);
				
				$profiles = [
                    "status" => "success",
                    "config" => $this->config,
                    "result" => $result
                ];
            }

        } catch (Exception $e) {
            $profiles = [
                    "status" => "error",
                    "config" => $this->config,
                    "message" => $e->getMessage(),
                    "save_url" => ""
                ];
        }
		$data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            "content" => view('Core\Reddit_profiles\Views\add', $profiles)
        ];
		return view('Core\Reddit_profiles\Views\index', $data);
	}
	
	

	public function oauth()
	{
        get_session("readdit_access_token");
        redirect($this->reddit->getAuthorizeURL());
	}

	public function save()
	{
		try {
            $ids = post('id');
            $team_id = get_team("id");

            validate('empty', __('Please select a profile to add'), $ids);

            $access_token = get_session("readdit_access_token");

            //
            $this->reddit->setAccessToken($access_token);
            $profile = $this->reddit->getUser();

            if($ids[0] == $profile->subreddit->display_name){
                $item = db_get('*', TB_ACCOUNTS, "social_network = 'reddit' AND team_id = '{$team_id}' AND pid = '{$profile->id}'");
                $avatar = save_img( $profile->icon_img, WRITEPATH.'avatar/' );

                if(!$item){
                    $data = [
                        'ids' => ids(),
						'module' => $this->module,
                        'social_network' => 'reddit',
                        'category' => 'profile',
                        'login_type' => 1,
                        'can_post' => 1,
                        'team_id' => $team_id,
                        'pid' => $profile->subreddit->display_name,
                        'name' => $profile->name,
                        'username' => $profile->subreddit->display_name,
                        'token' => $access_token,
                        'avatar' => $avatar,
                        'url' => 'https://www.reddit.com/user/'.$profile->name,
                        'data' => NULL,
                        'status' => 1,
                        'changed' => now(),
                        'created' => now()
                    ];

                    check_number_account("reddit", "profile");

                    db_insert(TB_ACCOUNTS, $data);
                }else{
                    @unlink($item->avatar);

                    $data = [
                        'social_network' => 'reddit',
                        'category' => 'profile',
                        'login_type' => 1,
                        'can_post' => 1,
                        'team_id' => $team_id,
                        'pid' => $profile->subreddit->display_name,
                        'name' => $profile->name,
                        'username' => $profile->subreddit->display_name,
                        'token' => $access_token,
                        'avatar' => $avatar,
                        'url' => 'https://www.reddit.com/user/'.$profile->name,
                        'status' => 1,
                        'changed' => now(),
                    ];

                    db_update(TB_ACCOUNTS, $data, ['id' => $item->id]);
                }

                get_session('readdit_access_token');
                
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
        } catch (Exception $e) {
            ms([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
	}
}