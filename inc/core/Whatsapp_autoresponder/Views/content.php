<form class="actionForm" action="<?php _e( get_module_url("info") )?>" method="POST" data-result="html" data-content="ajax-result" date-redirect="false" data-loading="true">
    
    <div class="container my-5 mw-1000">
        <div class="mb-5">
            <h2><i class="<?php _ec( $config['icon'] )?> me-2" style="color: #FF5C35"></i> <?php _ec( $config['name'] )?></h2>
            <p><?php _e( $config['desc'] )?></p>
        </div>

        <div class="card b-r-10 mb-4">
            <div class="card-body p-10">
                
                <select name="account" data-control="select2" data-hide-search="true" class="form-select form-select-sm bg-body fw-bold border-0 miw-130 auto-submit">
                    <option value="" data-icon="fab fa-whatsapp" data-icon-color="#25d366"><span><?php _e("Select WhatsApp account")?></span></option>
                    <option value="all" data-icon="fab fad fa-check-double text-primary" data-icon-color=""><span><?php _e("Apply for all accounts")?></span></option>
                    <?php if (!empty($accounts)): ?>

                        <?php foreach ($accounts as $key => $value): ?>
                            <option value="<?php _ec( $value->ids )?>" data-img="<?php _ec( get_file_url( $value->avatar ) )?>" ><?php _ec( $value->name )?></option>
                        <?php endforeach ?>
                        
                    <?php else: ?>
                        
                    <?php endif ?>
                </select>

            </div>
        </div>

        <div class="ajax-result">
            <?php _ec( $this->include('Core\Whatsapp\Views\empty'), false);?>
        </div>

    </div>

</form>
