<?php
/**
 * Archive and search loop card.
 *
 * @package justg
 */

defined('ABSPATH') || exit;
?>

<article <?php post_class('card border-0 rounded-3 shadow-sm p-3'); ?> id="post-<?php the_ID(); ?>">
    <div class="row g-3 align-items-start">
        <div class="col-md-4 m-0">
            <?php velocity_sekolah4_post_thumbnail(get_the_ID(), 'ratio-4x3', 'rounded-2'); ?>
        </div>

        <div class="col-md-8 mt-md-0">
            <header class="entry-header mb-2">
                <?php
                the_title(
                    sprintf(
                        '<h2 class="card-title fs-5 fw-semibold lh-sm mb-0"><a class="text-decoration-none" href="%s" rel="bookmark">',
                        esc_url(get_permalink())
                    ),
                    '</a></h2>'
                );
                ?>
            </header>

            <?php if ('post' === get_post_type()) : ?>
                <div class="entry-meta d-flex flex-wrap gap-2 text-body-secondary small mb-3">
                    <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                    <span aria-hidden="true">&bull;</span>
                    <span>
                        <?php esc_html_e('Oleh', 'justg'); ?>
                        <a class="text-body-secondary" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                            <?php echo esc_html(get_the_author()); ?>
                        </a>
                    </span>
                </div>
            <?php endif; ?>

            <p class="card-text text-body-secondary mb-0">
                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 24, '…')); ?>
            </p>
        </div>
    </div>
</article>
