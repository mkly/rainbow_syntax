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

class RainbowSyntaxC5BlockJsController extends BlockController
{

    protected $blockJsAccessible  = null;
    protected $blockJsAddToHeader = false;

    public function on_page_view()
    {
        $this->set('blockJsId', $this->getBlockJsIdentifier());
        if ($this->blockJsAddToHeader) {
            $this->addHeaderItem(
                '<script type="text/javascript">'.
                    $this->buildBlockJsDefinition().
                    $this->buildBlockJsInit().
                '</script>'
            );
        } else {
            $this->addFooterItem(
                '<script type="text/javascript">'.
                    $this->buildBlockJsDefinition().
                    $this->buildBlockJsInit().
                '</script>'
            );
        }
    }

    protected function getBlockJsExtraData()
    {
    }

    protected function getBlockJsData() 
    {
        $blockData           = new StdClass;

        $blockControllerData = $this->getBlockControllerData();
        $attributeNames      = $blockControllerData->getAttributeNames();
        foreach ($attributeNames as $key) {
            $blockData->{$key} = $blockControllerData->{$key};
        }

        if (!$this->blockJsAccessible) {
            return $blockData;
        }

        $result = new StdClass;
        foreach ($blockJsAccessible as $key) {
            if(!isset($blockData->{$key})) {
                continue;
            }
            $result->{$key} = $blockData->{$key};
        }

        return $result;
    }

    protected function buildBlockJsDefinition()
    {
        $blk  = new StdClass;
        $name = $this->getBlockJsIdentifier();

        $blk->bID        = $this->bID;
        $blk->identifier = $name;
        
        ( $blk->data     = $this->getBlockJsData())
        ||$blk->data     = new StdClass;

        ( $blk->extra    = $this->getBlockJsExtraData())
        ||$blk->extra    = new StdClass;

        ( $blk->actions  = $this->getBlockJsActions())
        ||$blk->actions  = new StdClass;

        $json = json_encode($blk);

        return "var $name = $json;";
    }

    protected function buildBlockJsInit()
    {
        $class = Loader::helper('text')->camelcase($this->btHandle);
        return ''.
          '$(function() {'.
            '$.extend('.$this->getBlockJsIdentifier().', '.$class.');'.
            $this->getBlockJsIdentifier().'.init();'.
          '});'
        ;
    }

    protected function getBlockJsIdentifier()
    {
        return $this->getIdentifier().'_BlockJs';
    }

    protected function getBlockJsActions()
    {
        $actions = new StdClass;

        foreach (get_class_methods($this) as $method) {
            if (strpos($method, 'action_') === 0) {
                $action = preg_replace('/^action_/', '', $method);
                $actions->{$action} = $this->getBlockJsPassThruAction($action);
            }
        }

        return $actions;
    }

    protected function getBlockJsPassthruAction($action)
    {
        $db       = Loader::db();
        $c        = Page::getCurrentPage();
        $cID      = $c->getCollectionID();
        $cvID     = $c->getVersionID();
        $bID      = $this->bID;
        $token    = Loader::helper('validation/token')->generate();
        $urlstart = DIR_REL.'/'.DISPATCHER_FILENAME;
        $ctHandle = $this->getBlockObject()
                         ->getBlockCollectionObject()
                         ->getCollectionTypeHandle();

        if ($ctHandle === STACKS_PAGE_TYPE) {
            $stackID = $db->GetOne(
                         'SELECT cID '.
                         'FROM CollectionVersionBlocks '.
                         'WHERE bID=?',
                         array($bID)
                       );
            $btask   = 'passthru_stack';

            return ''.
              $urlstart.
              "?cID=$cID&stackID=$stackID&bID=$bID".
              "&btask=$btask&ccm_token=$token&method=$action"
            ;

        }

        $btask  = 'passthru';

        $area   = $db()->GetOne(
                    'SELECT arHandle '.
                    'FROM CollectionVersionBlocks '.
                    'WHERE cID=? AND cvID=? AND bID=?',
                    array(
                      $cID,
                      $cvID,
                      $bID
                    )
                  );
        $area   = urlencode($area);

        ( $step = '&step='.$_REQUEST['step'])
        ||$step = '';

        return ''.
          $urlstart.
          "?cID=$cID&bID=$bID&arHandle=$area".
          "$step&ccm_token=$token&btask=$btask&method=$action"
        ;
    }
                
}
