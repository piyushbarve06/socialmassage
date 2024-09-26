<?php if (role($config['id'])): ?>
<div class="nav-item custom-hover-effect mb-2">
    <a class="nav-link sp-menu-item <?php _e( uri('segment', 3) == $config['id']?"active":"" )?>" href="<?php _e( base_url("mail") )?>">
       
            <i class="<?php _ec( $config['icon'] )?> fs-20 dashboard-icon me-4"></i>
       
        <span class="text-gray-600 fw-5" style="font-size:14px"><?php _e("Email Configuration")?></span>
    </a>
</div>
<?php endif ?>