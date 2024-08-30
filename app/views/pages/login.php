<?php
require_once "../app/bootstrap.php";
include '../app/views/inc/header.php';
?>
        <!-- Start main-content -->
        <main class="main-content dt-sl mt-4 mb-3">
            <div class="container main-container">

                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-7 col-12 mx-auto">
                        <div class="form-ui dt-sl dt-sn pt-4"> 
                            <div class="section-title title-wide mb-1 no-after-title-wide">
                                <h2 class="font-weight-bold">ورود به دی دی کالا</h2>
                            </div>
                            <form method="post">
                                <div class="form-row-title">
                                    <h3>نام کاربری</h3>
                                </div>
                                <div class="form-row with-icon">
                                    <input required name="username" type="text" class="input-ui pr-2" placeholder="نام کاربری خود را وارد نمایید">
                                    <i class="mdi mdi-account-circle-outline"></i>
                                </div>
                                <div class="form-row-title">
                                    <h3>رمز عبور</h3>
                                </div>
                                <div class="form-row with-icon">
                                    <input required name="password" type="password" class="input-ui pr-2" placeholder="رمز عبور خود را وارد نمایید">
                                    <i class="mdi mdi-lock-open-variant-outline"></i>
                                </div>
                                <div class="form-row mt-3">
                                    <button class="btn-primary-cm btn-with-icon mx-auto w-100">
                                        <i class="mdi mdi-login-variant"></i>
                                        ورود به دی دی کالا
                                    </button>
                                </div>
                                <div class="form-footer text-right mt-3">
                                    <span class="d-block font-weight-bold">کاربر جدید هستید؟</span>
                                    <a href="/pages/register" class="d-inline-block mr-3 mt-2">ثبت نام در دی دی کالا</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <!-- End main-content -->
<?php
include '../app/views/inc/footer.php';
?>