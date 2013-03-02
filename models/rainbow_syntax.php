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

class RainbowSyntax
{

    protected $package;

    protected $themeOptions = array(
        'all-hallows-eve' => 'All Hallows Eve',
        'blackboard'      => 'Blackboard',
        'espresso-libre'  => 'Espresso Libre',
        'github'          => 'Github',
        'obsbidian'       => 'Obsidian',
        'pastie'          => 'Pastie',
        'solarized-dark'  => 'Solarized Dark',
        'solarized-light' => 'Solarized Light',
        'sunburst'        => 'Sunburst',
        'tomorrow-night'  => 'Tomorrow Night',
        'tricolore'       => 'Tri Colore',
        'twilight'        => 'Twilight',
        'zenburnesque'    => 'Zenburnesque'
    );

    protected $languageOptions = array(
        'c'            => 'C',
        'coffeescript' => 'CoffeeScript',
        'csharp'       => 'C#',
        'generic'      => 'Generic',
        'go'           => 'Go',
        'haskell'      => 'Haskell',
        'html'         => 'HTML',
        'java'         => 'Java',
        'javascript'   => 'Javascript',
        'lua'          => 'Lua',
        'php'          => 'PHP',
        'r'            => 'R',
        'ruby'         => 'Ruby',
        'scheme'       => 'Scheme',
        'shell'        => 'Shell',
        'smalltalk'    => 'Smalltalk'
    );

    public function __construct()
    {
        $this->package = Package::getByHandle('rainbow_syntax');
    }

    public function getThemeOptions()
    {
        return $this->themeOptions;
    }

    public function getTheme()
    {
        return $this->package->config('theme');
    }

    public function setTheme($theme)
    {
        $this->package->saveConfig('theme', $theme);
        return true;
    }

    public function getLanguageOptions()
    {
        return $this->languageOptions;
    }

    public function getLanguageDefault()
    {
        return $this->package->config('language');
    }

    public function setLanguageDefault($language)
    {
        $this->package->saveConfig('language', $language);
        return true;
    }

    public function validateSettings($args)
    {
        $error = Loader::helper('validation/error');

        if (!$args['language']) {
            $error->add(t('Language is required'));
        }
        if (!array_key_exists($args['language'], $this->getLanguageOptions())) {
            $error->add(t('Unknown language'));
        }

        if (!$args['theme']) {
            $error->add(t('Theme is required'));
        }
        if (!array_key_exists($args['theme'], $this->getThemeOptions())) {
            $error->add(t('Unknown theme'));
        }

        if ($error->has()) {
            return $error;
        }

        return true;
    }

    public function saveSettings($args)
    {
        $res = $this->validateSettings($args);
        if ($res !== true) {
            return $res;
        }
            
        if (isset($args['language'])) {
            $this->setLanguageDefault($args['language']);
        }
        if (isset($args['theme'])) {
            $this->setTheme($args['theme']);
        }

        return true;
    }

    public function getRainbowJsFile($minified = true)
    {
        if ($minified) {
            return $this->package->getRelativePath().'/js/rainbow.min.js';
        }
        return $this->package->getRelativePath().'/js/rainbow.js';
    }

    public function getLanguageJsFile($language = false, $minified = true)
    {
        (!$language) && $language = $this->getLanguageDefault();
        return $this->package->getRelativePath()."/js/language/{$language}.js";
    }

    public function getLanguageJsGenericFile($minified = true)
    {
        return $this->package->getRelativePath().'/js/language/generic.js';
    }

    public function getThemeCssFile()
    {
        (!$css) && $css = $this->getTheme();
        return $this->package->getRelativePath()."/css/{$css}.css";
    }

    public function validateBlockRainbowJs($args)
    {
        $error = Loader::helper('validation/error');

        if (!$args['langauge']) {
            $error->add(t('Language Required'));
        }

        if (!in_array($args['language'], $this->getLanguageOptions())) {
            $error->add(t('Unknown Language'));
        }

        if (!$args['code']) {
            $error->add(t('Code Required'));
        }

        if (strlen($args['code']) > 4294967295) {
            $error->add(t('Code must be less than 4,294,967,296 characters'));
        }

        if (!$error->has()) {
            return $error;
        }
        return true;
    }
}
