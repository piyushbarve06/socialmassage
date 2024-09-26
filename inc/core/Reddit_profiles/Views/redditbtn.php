<?php 
$state = rand();
$url =  "https://www.reddit.com/api/v1/authorize?client_id=uoWysGDQX_HfLxONqGQCCw&response_type=code&state=$state&redirect_uri=https://app2.postglance.com/reddit_profiles&duration=permanent&scope=save,modposts,identity,edit,flair,history,modconfig,modflair,modlog,modwiki,mysubreddits,privatemessages,read,report,submit,subscribe,vote,wikiedit,wikiread,identity"
?>
<div class="btn btn-outline btn-outline-dashed me-2 mb-2 text-start list-btn-add-account d-flex justify-content-between align-items-center py-2">
    <a href="<?php _e($url)?>" class="text-gray-800">
        <div>
            <i class="<?php _e( $config['icon'] )?>" style="color: <?php _e( $config['color'] )?>;" ></i> 
            <?php _e("Add Reddit profile")?>
        </div>
    </a>
</div>