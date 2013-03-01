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

class DashboardRainbowSyntaxSettingsController extends DashboardBaseController
{
    public function view()
    {
        $this->set('dh'           , Loader::helper('concrete/dashboard'));
        $this->set('ih'           , Loader::helper('concrete/interface'));
        $this->set('RainbowSyntax', new RainbowSyntax);
    }

    public function update()
    {
        $RainbowSyntax = new RainbowSyntax;
        $res = $RainbowSyntax->saveSettings(
            array(
                'language' => $this->post('language'),
                'theme'    => $this->post('theme')
            )
        );

        if ($res === true) {
            $this->set('message', t('Settings Updated'));
        } else {
            $this->set('error', $res);
        }

        $this->view();
    }
}
