<?php

use \didikala\models\Product;
use \didikala\models\Category;

$product = $data['products'];

require_once "../app/bootstrap.php";
include '../app/views/inc/header.php';
?>
<!-- Start main-content -->
<main class="main-content dt-sl mt-4 mb-3">
    <div class="container main-container">
        <?php //TODO: First - complete comment = method post and process  ?>
        <!-- Start Product -->
        <div class="dt-sn mb-5 dt-sl">
            <div class="row">
                <!-- Product Thumbnail-->
                <div class="col-lg-4 col-md-6">
                    <div class="product-thumbnail text-center">
                        <a href="/product/detail/<?= $product->id ?>">
                            <img src="/public/<?= $product->thumbnail ?>" class="img-fluid" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product-info dt-sl">
                        <div class="product-title dt-sl">
                            <h1><?= $product->title ?></h1>
                        </div>
                        <div class="description-product dt-sl mt-3 mb-3">
                            <div class="container">
                                <p><?= $product->content ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form class="w-50 mx-auto" method="post">
                <div class="mt-2">
                    <label class="d-block" for="strengths">نقاط قوت</label>
                    <input name="strengths" class="form-control" type="text" id="strengths" placeholder="مزیت اول/مزیت دوم/مزیت سوم"></input>
                </div>
                <div class="mt-2">
                    <label class="d-block" for="weaknesses">نقاط ضعف</label>
                    <input name="weaknesses" class="form-control" type="text" id="weaknesses" placeholder="عیب اول/عیب دوم/عیب سوم"></input>
                </div>
                <div class="mt-2">
                    <label class="d-block" for="content">متن نظر شما (اجباری)</label>
                    <textarea required name="content" class="form-control" id="content" rows="5" placeholder="متن خود را بنویسید"></textarea>
                </div>
                <div class="mt-2 mb-2">
                    <label class="font-weight-bold d-block">
                        آیا خرید این محصول را به دیگران
                        پیشنهاد می کنید؟
                    </label>
                    <label for="re_commendation">
                        پیشنهاد می کنم
                    </label>
                    <input type="radio" id="re_commendation" value="1" name="commendation" class="form-control d-inline" style="width: 15px; height: 15px">
                    <br>
                    <label for="de_commendation">
                        خیر،پیشنهاد نمی کنم
                    </label>
                    <input type="radio" id="de_commendation" value="-1" name="commendation" class="form-control d-inline" style="width: 15px; height: 15px">
                    <br>
                    <label for="none_commendation">
                        نظری ندارم
                    </label>
                    <input checked type="radio" id="none_commendation" value="0" name="commendation" class="form-control d-inline" style="width: 15px; height: 15px">
                </div>
                <div>
                    <button class="btn btn btn-primary px-3">
                        ثبت نظر
                    </button>
                </div>
            </form>
        </div>
        <!-- End Product -->

    </div>
</main>
<!-- End main-content -->
<?php include '../app/views/inc/footer.php'; ?>
