<?php
/**
 * Rainbow Syntax Highligher
 * A concrete5 Package to insert syntax highlighted blocks
 * Built with the Rainbow javascript library by Craig Campbell
 *
 * @author    Mike Lay
 * @copyright 2013 Mike Lay
 * @link      http://github.com/mkly/rainbow_syntax
 * @license   Apache 2.0
 * @package   RainbowSyntax
 */
defined('C5_EXECUTE') or die('Access Denied.');

class RainbowSyntaxBlockController extends RainbowSyntaxC5BlockJsController
{

    protected $btTable = 'btRainbowSyntax';
    protected $btInterfaceWidth = '450';
    protected $btInterfaceHeight = "400";
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

    public function getBlockTypeName()
    {
        return t('Rainbow Syntax Highlighter');
    }

    public function getBlockTypeDescription()
    {
        return t('Insert a code block highlighted by the Rainbow javascript library');
    }
    
    public function on_page_view()
    {
        $RainbowSyntax = new RainbowSyntax;
        $html = Loader::helper('html');

        $this->addHeaderItem(
            $html->javascript(
                $RainbowSyntax->getRainbowJsFile()
            )
        );

        $this->addHeaderItem(
            $html->javascript(
                $RainbowSyntax->getLanguageJsGenericFile()
            )
        );

        $this->addHeaderItem(
            $html->javascript(
                $RainbowSyntax->getLanguageJsFile($this->language)
            )
        );

        $this->addHeaderItem(
            $html->css(
                $RainbowSyntax->getThemeCssFile($this->theme)
            )
        );

        parent::on_page_view();
    }

    public function add()
    {
        $RainbowSyntax = new RainbowSyntax;
        $this->set('language', $RainbowSyntax->getLanguageDefault());
        $this->edit();
    }

    public function edit()
    {
        $this->set('RainbowSyntax', new RainbowSyntax);
    }

    public function validate($args)
    {
        $RainbowSyntax = new RainbowSyntax;
        return $RainbowSyntax->validateBlockRainbowJs($args);
    }

}
