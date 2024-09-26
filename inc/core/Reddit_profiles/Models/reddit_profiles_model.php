<?php
namespace Core\Reddit_profiles\Models;
use CodeIgniter\Model;

class reddit_profiles_model extends Model
{
	public function __construct(){
		$this->config = include realpath( __DIR__."/../Config.php" );
	}

	public function block_accounts($path = ""){
		$team_id = get_team("id");
        $accounts = db_fetch("*", TB_ACCOUNTS, "social_network = 'reddit' AND category = 'profile' AND team_id = '{$team_id}'");
		 return [
        	"button" => view( 'Core\Reddit_profiles\Views\redditbtn', [ 'config' => $this->config ] ),
        	"content" => view( 'Core\Reddit_profiles\Views\content', [ 'config' => $this->config, 'accounts' => $accounts ] )
        ];
	}

	public function block_social_settings($path = ""){
        return [
            "menu" => view( 'Core\Reddit_profiles\Views\settings\menu', [ 'config' => $this->config ] ),
            "content" => view( 'Core\Reddit_profiles\Views\settings\configuration', [ 'config' => $this->config ] )
        ];
    }
}
