<div class="sub-sidebar d-flex flex-column flex-row-auto" style="max-width:19rem;height:100%">
    <div class="d-flex mb-10 p-20">
        <div class="d-flex align-items-center w-lg-400px">
            <form class="w-100 position-relative mb-lg-0">
                <div class="input-group sp-input-group">
                  <span class="input-group-text bg-light border-0 fs-20 bg-gray-100 text-gray-800" id="sub-menu-search"><i class="fad fa-search"></i></span>
                  <input type="text" class="form-control form-control-solid ps-15 bg-light border-0 search-input" data-search="sp-menu-item" name="search" value="" placeholder="<?php _e("Search")?>" autocomplete="off">
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex mb-10 p-l-20 p-r-20 m-b-12">
        <h2 class="text-gray-800 fw-bold"><?php _e( $title )?></h2>
    </div>

    <div class="sp-menu n-scroll sp-menu-two menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 p-l-20 p-r-20 m-b-12 fw-5">
        <div class="d-none menu-item">
            <div class="menu-content pb-2">
                <span class="menu-section text-muted text-uppercase fs-12 ls-1">Dashboard</span>
            </div>
        </div>

        <?php 
        if( !empty($settings) ){
            foreach ($settings as $setting) {
                if( isset($setting['menu']) ){
                    echo $setting['menu'];
                }
            }
        }
        ?>
    </div>
</div>