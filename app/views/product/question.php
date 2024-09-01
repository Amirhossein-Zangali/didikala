<?php

use \didikala\models\Product;
use \didikala\models\Category;

$product = $data['products'];
$reply = $data['reply'] ?? false;

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
                <div class="my-2">
                    <?php if ($reply) :
                        $reply_comment = (new \didikala\models\Comment())->getQuestion($reply);
                    ?>
                    <h5><?= $reply_comment->content ?></h5>
                    <?php endif; ?>
                    <label class="d-block" for="question">متن <?= $reply ? 'پاسخ' : 'پرسش'?> شما (اجباری)</label>
                    <textarea required name="question" class="form-control" id="question" rows="5" placeholder="متن <?= $reply ? 'پاسخ' : 'پرسش'?> خود را بنویسید"></textarea>
                    <input type="hidden" name="reply_id" value="<?= $reply ?>">
                </div>
                <div>
                    <button class="btn btn btn-primary px-3">
                        ثبت <?= $reply ? 'پاسخ' : 'پرسش'?>
                    </button>
                </div>
            </form>
        </div>
        <!-- End Product -->

    </div>
</main>
<!-- End main-content -->
<?php include '../app/views/inc/footer.php'; ?>
