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

class RainbowSyntaxPackage extends Package
{

    protected $pkgHandle = "rainbow_syntax";
    protected $appVersionRequired = "5.6";
    protected $pkgVersion = "0.1";

    public function getPackageName()
    {
        return t('Rainbow Syntax Highlighter');
    }

    public function getPackageDescription()
    {
        return t('Syntax highlighting using the Rainbow javascript library created by Craig Campbell');
    }

    public function on_start()
    {
        Loader::registerAutoload(array(
            'RainbowSyntaxC5BlockJsController' => array(
                'library',
                'rainbow_syntax_c5_block_js_controller',
                'rainbow_syntax'
            ),
            'RainbowSyntax' => array(
                'model',
                'rainbow_syntax',
                'rainbow_syntax'
            )
        ));

    }

    public function install()
    {
        $pkg = parent::install();

        $this->on_start();

        if (!BlockType::getByHandle('rainbow_syntax')) {
            BlockType::installBlockTypeFromPackage('rainbow_syntax', $pkg);
        } else {
            Log::addEntry(
                t(
                    'Rainbow Syntax Block Type already installed. '.
                    'Skipping installation.'
                ),
                'rainbow_syntax'
            );
        }

        if (Page::getByPath('/dashboard/rainbow_syntax/settings')->isError()) {
            $sp = SinglePage::add('/dashboard/rainbow_syntax/settings', $pkg);
            $sp->update(
                array(
                    'cName'        => t('Settings'),
                    'cDescription' => t('Rainbow syntax highlighter settings')
                )
            );
            $sp->setAttribute('icon_dashboard', 'icon-wrench');
        } else {
            Log::addEntry(
                t(
                    'Rainbow Syntax Single Page "settings" already installed. '.
                    'Skipping installation.'
                ),
                'rainbow_syntax'
            );
        }

        $RainbowSyntax = new RainbowSyntax;
        $RainbowSyntax->setTheme('github');
        $RainbowSyntax->setLanguageDefault('php');

    }   
}
