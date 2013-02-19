<?php
/**
 * Rainbow Syntax Highligher
 * A concrete5 Package to insert syntax highlighted blocks
 * Built with the Rainbow javascript library by Craig Campbell
 *
 * @author    Mike Lay
 * @copyright 2013 Mike Lay
 * @link      http://github.com/mkly/c5_addon_rainbow_syntax
 * @license   Apache 2.0
 * @package   RainbowSyntax
 */
defined('C5_EXECUTE') or die('Access Denied.');
?>
<?= $dh->getDashboardPaneHeaderWrapper(t('Rainbow Syntax Highlighter'), t('Set things for things'), false, false, false) ?>

<form method="post" action="<?= $this->url('/dashboard/rainbow_syntax/settings', 'update') ?>">

<div class="ccm-pane-body">

    <fieldset>
        <legend><?= t('Settings') ?></legend>

        <div class="clearfix">
            <?= $form->label('language', t('Default Language')) ?>
            <div class="input">
                <?= $form->select('language', $RainbowSyntax->getLanguageOptions(), $RainbowSyntax->getLanguageDefault()) ?>
            </div><!-- .input -->
        </div><!-- .clearfix -->
            
        <div class="clearfix">
            <?= $form->label('theme', t('Theme')) ?>
            <div class="input">
                <?= $form->select('theme', $RainbowSyntax->getThemeOptions(), $RainbowSyntax->getTheme()) ?>
            </div><!-- .input -->
        </div><!-- .clearfix -->
            
    </fieldset>

</div><!-- .ccm-pane-body -->

<div class="ccm-pane-footer">
    <?= $ih->submit(t('Update Settings'), false, 'right', 'primary') ?>
</div><!-- .ccm-pane-footer-->

</form>

<?= $dh->getDashboardPaneFooterWrapper(false) ?>
