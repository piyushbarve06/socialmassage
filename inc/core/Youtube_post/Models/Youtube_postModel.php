<?php
namespace Core\Youtube_post\Models;
use CodeIgniter\Model;

class Youtube_postModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
        
        $client_id = get_option('youtube_client_id', '');
        $client_secret = get_option('youtube_api_secret', '');
        $api_key = get_option('youtube_api_key', '');

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

    public function block_can_post(){
        return true;
    }

    public function block_plans(){
        return [
            "tab" => 10,
            "position" => 400,
            "permission" => true,
            "label" => __("Planning and Scheduling"),
            "items" => [
                [
                    "id" => $this->config['id'],
                    "name" => sprintf("%s scheduling & report", $this->config['name']),
                ]
            ]
        ];
    }

    public function block_frame_posts($path = ""){
        return [
            "position" => 400,
        	"preview" => view( 'Core\Youtube_post\Views\preview', [ 'config' => $this->config ] ),
            "advance_options" => view( 'Core\Youtube_post\Views\advance_options', [ 'config' => $this->config ] )
        ];
    }

    public function post_validator($post){
        $errors = array();
        $data = json_decode( $post->data , 1);
        $medias = $data['medias'];

        if($post->social_network == 'youtube'){
            if( !isset( $data['advance_options'] ) || !isset( $data['advance_options']['youtube_title'] ) ||  $data['advance_options']['youtube_title'] == ""){
                $errors[] = __("A title for the post on Youtube is mandatory");
            }

            switch ($post->type) {

                case 'text':
                    $errors[] = __("Youtube does not support posting as text");
                    break;

                case 'link':
                    $errors[] = __("Youtube does not support posting as link");
                    break;
                
                case 'media':
                    if(empty($data['medias'])){
                        $errors[] = __("Youtube just support posting as video");
                    }else{
                        if(!is_video($medias[0]))
                        {
                            $errors[] = __("Youtube just support posting as video");
                        }
                    }
                    break;
            }
        }

        return $errors;
    }

    public function post_handler($post){
        $data = json_decode($post->data, false);
        $medias = $data->medias;
        $shortlink_by = shortlink_by($data);

        //$this->client->setAccessToken($post->account->token);

        try
        {
            switch ($post->type)
            {
                case 'media':
                    if(count($medias) == 0)
                    {
                        return [
                            "status" => "error",
                            "message" => __("Cannot find the video to upload"),
                            "type" => $post->type
                        ];
                    }

                    if(!is_video($medias[0]))
                    {
                        return [
                            "status" => "error",
                            "message" => __("Cannot find the video to upload"),
                            "type" => $post->type
                        ];
                    }
 
                    $videoPath = get_file_path($medias[0]);
                    $caption = shortlink( spintax($data->caption), $shortlink_by);
                    if( isset( $data->advance_options ) && isset( $data->advance_options->youtube_title ) && $data->advance_options->youtube_title != ""){
                        $title = spintax($data->advance_options->youtube_title);
                    }else{
                        $title = $caption;
                    }
					$categoryId = $data->advance_options->youtube_category;
					$accessToken = $post->account->token;
					$tags= $data->advance_options->youtube_tags;
					$discription = $caption;
					
					    // Step 1: Define the video snippet and status
// Step 1: Define the video snippet and status
    $snippet = [
        'title' => $title,
        'description' => $caption,
        'tags' => $tags,
        'categoryId' => $categoryId,
    ];
    
    $status = [
        'privacyStatus' => 'public',
    ];

    $postData = [
        'snippet' => $snippet,
        'status' => $status,
    ];

    // Step 2: Start the resumable upload
    $url = "https://www.googleapis.com/upload/youtube/v3/videos?part=snippet,status&uploadType=resumable";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json; charset=UTF-8',
        'X-Upload-Content-Type: video/*',
        'X-Upload-Content-Length: ' . filesize($videoPath)
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    // Extract the upload URL from the response headers
    $uploadUrl = null;
    foreach (explode("\r\n", $headers) as $header) {
        if (stripos($header, 'Location:') !== false) {
            $uploadUrl = trim(str_replace('Location:', '', $header));
            break;
        }
    }

    if ($httpCode !== 200 && $httpCode !== 201) {
        return [
            'status' => 'error',
            'message' => 'Failed to initiate upload: ' . $body
        ];
    }

    if (!$uploadUrl) {
        return [
            'status' => 'error',
            'message' => 'Upload URL not returned'
        ];
    }

    // Step 3: Upload the video in chunks
    $chunkSizeBytes = 1 * 1024 * 1024; // 1MB per chunk
    $handle = fopen($videoPath, "rb");

    $ch = curl_init($uploadUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: video/*',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    
    $status = false;
    while (!$status && !feof($handle)) {
        $chunk = fread($handle, $chunkSizeBytes);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $chunk);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200 || $httpCode == 201) {
            $status = true;
        } else {
            $status = false;
        }
    }

    fclose($handle);
    
    if (!$status) {
        return [
            'status' => 'error',
            'message' => 'Failed to upload video'
        ];
	}
					

            }
            
        } catch(\Exception $e) {
            return [
                "status" => "error",
                "message" => __( $e->getMessage() ),
                "type" => $post->type
            ];
        }
    }
}
