<div class="row">
    <div class="col-lg-12">
        <div class="product__pagination">
            <?php if($page->current >= 3): ?>
              <a href="<?= $page->previous - 2 ?>"><?= $page->previous - 2 ?></a>
            <?php endif; ?>
            <?php if($page->current >= 2): ?>
              <a href="<?= $page->previous - 1 ?>"><?= $page->previous - 1 ?></a>
            <?php endif; ?>
            <?php if($page->current >= 1): ?>
              <a href="<?= $page->previous ?>"><?= $page->previous ?></a>
            <?php endif; ?>
            <a class="active"><?= $page->current ?></a>
            <?php if($$page->last - $page->current >= 1): ?>
              <a href="<?= $page->next ?>"><?= $page->next ?></a>
            <?php endif; ?>
            <?php if($page->last - $page->current >= 2): ?>
              <a href="<?= $page->next + 1 ?>"><?= $page->next + 1 ?></a>
            <?php endif; ?>
            <?php if($page->last - $page->current >= 3): ?>
              <a href="<?= $page->next + 2 ?>"><?= $page->next + 2 ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>