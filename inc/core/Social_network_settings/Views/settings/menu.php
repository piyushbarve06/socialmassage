<?php if (role($config['id'])): ?>
<div class="nav-item custom-hover-effect mb-2">
    <a class="nav-link sp-menu-item <?php _ec( uri('segment', 3) == $config['id']?"active":"" )?>" href="<?php _ec( base_url( $config['id'] ) )?>" data-remove-other-active="true" data-active="active" data-result="html" data-content="main-wrapper">
       
            <i class=""></i>
            <i class="dashboard-icon me-4 fs-20 <?php _ec( $config['icon'] )?>"></i>
     
        <span class="text-gray-600 fw-5" style="font-size:14px"><?php _e( $config['name'] )?></span>
    </a>
</div>
<?php endif ?>