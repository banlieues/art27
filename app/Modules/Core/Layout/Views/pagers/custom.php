<?php

/**
 * bs_full.php - Bootstrap 5.0.x Pager Template.
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */

$pager->setSurroundCount(3);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pager pagination pagination-sm justify-content-center my-0">
        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="page-item">
                <a class="page-link text-dark" href="<?= $pager->getFirst() ?>" 
                    aria-label="<?= lang('Pager.first') ?>"
                    >
                    <span aria-hidden="true"><?php echo fontawesome('angle-double-left');?></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link text-dark" href="<?= $pager->getPreviousPage() ?>" 
                    aria-label="<?= lang('Pager.previous') ?>"
                    >
                    <span aria-hidden="true"><?php echo fontawesome('angle-left');?></span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?php if(!empty($link['active'])):?> disabled <?php endif;?>">
                <a class="page-link <?php if(!empty($link['active'])):?> text-white fw-bold bg-dark <?php else:?> text-dark <?php endif;?>" 
                    href="<?= $link['uri'] ?>"
                    >
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNextPage()) : ?>
            <li class="page-item">
                <a class="page-link text-dark" 
                    href="<?= $pager->getNextPage() ?>" 
                    aria-label="<?= lang('Pager.next') ?>"
                    >
                    <span aria-hidden="true"><?php echo fontawesome('angle-right');?></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link text-dark" 
                    href="<?= $pager->getLast() ?>" 
                    aria-label="<?= lang('Pager.last') ?>"
                    >
                    <span aria-hidden="true"><?php echo fontawesome('angle-double-right');?></span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav> 
