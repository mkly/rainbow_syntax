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

<div class="ccm-ui">

<div class="control-group">
    <?= $form->label('code', t('Code')) ?>
    <div class="controls">
        <?= $form->textarea('code', $code, array('style' => 'width: 97%; height: 250px')) ?>
    </div><!-- .controls -->
</div><!-- .control-group -->

<div class="control-group">
    <?= $form->label('language', t('Language')) ?>
    <div class="controls">
        <?= $form->select('language', $RainbowSyntax->getLanguageOptions(), $language) ?>
    </div><!-- .controls -->
</div><!-- .control-group -->

</div><!-- .ccm-ui -->
