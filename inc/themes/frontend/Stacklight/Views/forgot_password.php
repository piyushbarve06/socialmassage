<div class="nk-app-root ">
        <main class="nk-pages">
            <div class="nk-split-page flex-column flex-xl-row">
                <div class="nk-split-col nk-auth-col">
                    <div class="nk-form-card card-gutter-md nk-auth-form-card mx-md-9 mx-xl-auto" data-aos="fade-up">
                        <div class="card-body">
                            <div class="nk-form-card-head pb-5">
                                <div class="form-logo mb-3">
                                    <a href="../">
                                        <img class="" src="<?php _ec( get_frontend_url() )?>Assets/img/logo-s1-dark.png" width="200" height="40" alt="">
                                    </a>
                                </div>
                                <h3 class="title mb-2">Password Forgotten?</h3>
                                <p class="text">To continue first verify it's you</p>
                        </div>
                            <form class="actionForm" action="<?php _ec( base_url("auth/forgot_password") )?>" method="POST">
                                <div class="row gy-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="email" class="form-control" placeholder="<?php _e("Enter your email")?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(get_option('google_recaptcha_status', 0)){?>
                                    <div class="g-recaptcha  mb-3" data-sitekey="<?=get_option('google_recaptcha_site_key', '')?>"></div>
                                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                    <?php }?>
                                    <div class="show-message mb-2"></div>
                                    <div class="col-12">
                                        <div class="form-group">
                                        <button class="btn btn-block btn-primary" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                           
                        </div>
                        <p class="text" style="text-align:right">Don't have an account? <a href="<?php _ec( base_url("signup") )?>" class="btn-link text-primary">Signup</a>.</p>
                    </div>
                    <!-- .nk-form-card -->
                </div>
                <div class="nk-split-col nk-auth-col-content  bg-primary-gradient is-theme">
                  
                    <div class="nk-auth-content mx-md-9 mx-xl-auto">
                    <div class="nk-auth-content-inner">
                          <svg aria-hidden="true" width="40" height="40" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_2956_6856)"> <path d="M9.58918 5.54748C10.9406 5.30303 12.1862 4.65014 13.1606 3.6755C14.1351 2.70087 14.7923 1.4506 15.0449 0.0907181C15.0491 0.0656366 15.062 0.0428558 15.0812 0.0264246C15.1005 0.00999348 15.1249 0.000976562 15.1501 0.000976563C15.1754 0.000976562 15.1998 0.00999348 15.2191 0.0264246C15.2383 0.0428558 15.2512 0.0656366 15.2553 0.0907181C15.5081 1.45059 16.1653 2.70083 17.1398 3.67546C18.1143 4.65008 19.3599 5.30298 20.7113 5.54748C20.7362 5.55152 20.7588 5.56435 20.7751 5.58367C20.7915 5.60299 20.8004 5.62754 20.8004 5.65292C20.8004 5.6783 20.7915 5.70285 20.7751 5.72217C20.7588 5.7415 20.7362 5.75432 20.7113 5.75836C19.3598 6.00283 18.1142 6.65578 17.1397 7.63051C16.1652 8.60523 15.508 9.8556 15.2553 11.2156C15.2512 11.2407 15.2383 11.2634 15.2191 11.2799C15.1998 11.2963 15.1754 11.3053 15.1501 11.3053C15.1249 11.3053 15.1005 11.2963 15.0812 11.2799C15.062 11.2634 15.0491 11.2407 15.0449 11.2156C14.7922 9.85581 14.135 8.60568 13.1605 7.63117C12.1861 6.65666 10.9405 6.00388 9.58918 5.7595C9.5635 5.75632 9.53986 5.74379 9.52271 5.72427C9.50556 5.70474 9.49609 5.67957 9.49609 5.65349C9.49609 5.62742 9.50556 5.60224 9.52271 5.58272C9.53986 5.56319 9.5635 5.55066 9.58918 5.54748Z" fill="#BF2434"></path> <path d="M0.0891604 11.8773C1.44105 11.6327 2.68709 10.9798 3.66183 10.0051C4.63656 9.0304 5.29388 7.78013 5.54648 6.4203C5.55065 6.39522 5.56351 6.37244 5.58278 6.35601C5.60205 6.33958 5.62648 6.33057 5.65172 6.33057C5.67697 6.33057 5.7014 6.33958 5.72067 6.35601C5.73994 6.37244 5.7528 6.39522 5.75697 6.4203C6.00969 7.78023 6.66715 9.03056 7.64205 10.0052C8.61695 10.9799 9.86316 11.6328 11.2152 11.8773C11.2401 11.8813 11.2627 11.8942 11.279 11.9135C11.2954 11.9328 11.3043 11.9574 11.3043 11.9827C11.3043 12.0081 11.2954 12.0327 11.279 12.052C11.2627 12.0713 11.2401 12.0841 11.2152 12.0882C9.86316 12.3326 8.61695 12.9856 7.64205 13.9602C6.66715 14.9349 6.00969 16.1852 5.75697 17.5452C5.7528 17.5703 5.73994 17.593 5.72067 17.6095C5.7014 17.6259 5.67697 17.6349 5.65172 17.6349C5.62648 17.6349 5.60205 17.6259 5.58278 17.6095C5.56351 17.593 5.55065 17.5703 5.54648 17.5452C5.29388 16.1853 4.63656 14.9351 3.66183 13.9604C2.68709 12.9857 1.44105 12.3327 0.0891604 12.0882C0.0642845 12.0841 0.0416493 12.0713 0.0253112 12.052C0.00897307 12.0327 0 12.0081 0 11.9827C0 11.9574 0.00897307 11.9328 0.0253112 11.9135C0.0416493 11.8942 0.0642845 11.8813 0.0891604 11.8773Z" fill="#FF5C35"></path> <path d="M9.13016 20.2429C10.4824 19.9984 11.7289 19.3455 12.7039 18.3708C13.6789 17.3962 14.3364 16.1459 14.589 14.786C14.593 14.7608 14.6058 14.7379 14.6251 14.7214C14.6444 14.7049 14.6688 14.6958 14.6942 14.6958C14.7195 14.6958 14.744 14.7049 14.7632 14.7214C14.7825 14.7379 14.7953 14.7608 14.7993 14.786C15.0519 16.1459 15.7094 17.3962 16.6844 18.3708C17.6595 19.3455 18.9059 19.9984 20.2581 20.2429C20.283 20.2469 20.3057 20.2598 20.322 20.2791C20.3383 20.2984 20.3473 20.3229 20.3473 20.3483C20.3473 20.3737 20.3383 20.3982 20.322 20.4176C20.3057 20.4369 20.283 20.4497 20.2581 20.4537C18.906 20.6982 17.6596 21.351 16.6846 22.3255C15.7096 23.3001 15.052 24.5502 14.7993 25.9099C14.7953 25.9351 14.7825 25.958 14.7632 25.9745C14.744 25.9911 14.7195 26.0001 14.6942 26.0001C14.6688 26.0001 14.6444 25.9911 14.6251 25.9745C14.6058 25.958 14.593 25.9351 14.589 25.9099C14.3364 24.5501 13.6789 23.2998 12.7039 22.3251C11.7289 21.3505 10.4824 20.6975 9.13016 20.4531C9.1057 20.4486 9.08357 20.4356 9.06764 20.4164C9.0517 20.3972 9.04297 20.373 9.04297 20.348C9.04297 20.3229 9.0517 20.2987 9.06764 20.2795C9.08357 20.2603 9.1057 20.2473 9.13016 20.2429Z" fill="#FFA766"></path> </g> <defs> <clipPath id="clip0_2956_6856"> <rect width="20.8" height="26" fill="white"></rect> </clipPath> </defs> </svg>
                            <h1 class="mb-5 mt-2" style="font-family:Queens Medium, serif;color:#213343">Expand Your Social Presence</h1>
                            <div class="nk-auth-quote ms-sm-5">
                                <div class="nk-auth-quote-inner">
                                    <p class="small" style="color:#213343;font-size:0.85rem!important">I’m really impressed with Postglance sleek and intuitive interface. It simplifies scheduling and planning for your social media calendar, making organization a breeze. If you're looking to elevate your social media strategy, I highly recommend giving it a try!</p>
                                    <div class="media-group align-items-center pt-3">
                                        <div class="media media-md media-circle media-middle">
                                            <img src="<?php _ec( get_frontend_url() )?>Assets/img/t-1.jpg" alt="avatar">
                                        </div>
                                        <div class="media-text">
                                            <div class="h5 mb-0">John Michel</div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
       
       
    </div>