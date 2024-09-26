
<head>
    <style>
        .dashboard-icon{
	font-size: 20px;
    color: #ff5c35;
}
.text-gray-600 {
    color: var(--sp-text-gray-600) !important;
   margin-left: 0rem !important; */
   
}
.custom-hover-effect {
    position: relative;
    transition: background-color 0.3s ease-in-out;
}

.custom-hover-effect:hover {
    background-color: rgba(255, 219, 193, 0.5); /* #FFDBC1 with 50% transparency */
    border-radius: 5px; /* Optional: Add border-radius for smooth edges */
}

		.active {
    background-color: rgba(255, 219, 193, 0.5);
}
    </style>
</head>
<div class="nav-item custom-hover-effect mb-2">
    <a class="nav-link sp-menu-item actionItem <?php _e( uri('segment', 3) == $config['id']?"active":"" )?>" href="<?php _e( base_url("settings/index/" . $config['id']) )?>" data-remove-other-active="true" data-active="active" data-result="html" data-content="main-wrapper" data-history="<?php _e( base_url("settings/index/" . $config['id']) )?>">
       
            <i class="bi bi-houses fs-20 dashboard-icon me-4"></i>
       
        <span class="text-gray-600" style="font-size:14px"><?php _e("General")?></span>
    </a>
</div>