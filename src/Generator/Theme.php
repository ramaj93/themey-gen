<?php

/*
 * The MIT License
 *
 * Copyright 2016 Ramadan Juma.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace themey\Generator;

use themey\Helper\FileHelper;
use PHPHtmlParser\Dom;
use themey\Helper\Display;
use themey\Application;

/**
 * Description of Theme
 *
 * @author Ramadan Juma
 */
class Theme {

    public $name;
    public $layout_path;
    public $callback;
    public $current_layout;
    public $assets = [];
    public $output;

    public function __construct($name, $layout = false, $output = false) {
        $this->name = $name;
        $this->layout_path = $layout;
        $this->output = $output;
    }

    public function generateStructure() {
        $dirs = [
            'components',
            'widgets',
            'assets',
            'gii'
        ];
        $theme_path = Application::$workingDir . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . $this->name;
        FileHelper::createFolders($dirs, $theme_path);
    }

    public function generateAssets($layouts = []) {
        $asset_path = Application::$workingDir . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . "assets";
        $tpl_path = __DIR__ . "/../Templates/Asset.php";
        foreach ($layouts as $layout) {
            $layout_name = ucfirst($layout) . "Asset.php";
            $this->current_layout = $layout;
            $contents = $this->renderFile($tpl_path);
            file_put_contents($asset_path . DIRECTORY_SEPARATOR . $layout_name, $contents);
        }
    }

    public function generateLayout($name, $layout_path,$default_asset = false, $assets = []) {
        $dom = new Dom;
        $dom->setOptions([
            'removeScripts' => FALSE,
            'preserveLineBreaks' => TRUE
        ]);
        $root = Application::$workingDir;
        $dom->loadFromFile($layout_path);
        $theme_base = dirname($layout_path);
        $scripts = $dom->getElementsByTag("script");
        $links = $dom->getElementsByTag("link");
        $images = $dom->getElementsByTag("img");
        
        if ($default_asset == FALSE && count($assets)>0) {
            $default_asset = $assets[0];
        }
        if($default_asset == FALSE){
            $default_asset = "app";
        }

        $asset_css = [];
        $assets_path = $root . DIRECTORY_SEPARATOR . "web/themes" . DIRECTORY_SEPARATOR . $this->name;
        foreach ($links as $link) {
            $href = $link->getAttribute("href");
            if (strpos($href, "http") === FALSE) {
                $this->assets[$default_asset]['css'][] = "themes/$this->name/" . $href;
                $src_path = $theme_base . DIRECTORY_SEPARATOR . $href;
                //$asset_css[] = $base_name . "/" . basename($src_path);
                Display::writeInfoLine("copying $src_path to $assets_path/$href");
                FileHelper::copyFile($src_path, $assets_path . DIRECTORY_SEPARATOR . $href);
                $link->delete();
            }
        }
        $asset_js = [];
        foreach ($scripts as $script) {
            $href = $script->getAttribute("src");
            if (strpos($href, "http") === FALSE) {
                if ($href != FALSE) {
                    $this->assets[$default_asset]['js'][] = "themes/$this->name/" . $href;
                    $src_path = $theme_base . DIRECTORY_SEPARATOR . $href;
                    $base_name = $href;
                    //$asset_js[] = $base_name . "/" . basename($src_path);
                    Display::writeInfoLine("copying $src_path to $assets_path/$href");
                    if (!FileHelper::copyFile($src_path, $assets_path . DIRECTORY_SEPARATOR . $base_name)) {
                        Display::writeErrorLine("Error copying $src_path, check permissions or if it exists.");
                    }
                }
                $script->delete();
            }
        }

        foreach ($images as $img) {
            $href = $img->getAttribute("src");
            if (strpos($href, "http") === FALSE) {
                $src_path = $theme_base . DIRECTORY_SEPARATOR . $href;
                $base_name = $href;
                \themey\Helper\FileHelper::copyFile($src_path, $assets_path . DIRECTORY_SEPARATOR . $base_name);
            }
        }
        $str = "use yii\\helpers\\Html;\n";
        foreach ($assets as $asset) {
            $bundle_name = ucfirst($asset) . "Asset";
            $str .= "use app\\themes\\$this->name\\assets\\$bundle_name;\n\n";
            $str .= "$bundle_name::register(\$this);\n\n";
        }

        $rep = <<<HTML
<?php\n\n
/* @var \$this \yii\web\View */\n
$str
                
\$this->beginPage();?>\n
<html
HTML;
        //$rep = str_replace("{assets}", $str, $rep);
        $dom = str_replace("<html", $rep, $dom);
        $dom = str_replace("</head>", "<?= Html::csrfMetaTags(); ?>\n<?=\$this->head();?>\n</head>", $dom);
        $dom = str_replace("</body>", "<?=\$this->endBody();?>\n</body>", $dom);
        $dom = str_replace("</html>", "</html>\n<?=\$this->endPage();?>", $dom);
        $dom = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $dom);
        $layout_dir = $root . DIRECTORY_SEPARATOR . "views/layouts/$this->name";
        if (!file_exists($layout_dir)) {
            mkdir($layout_dir);
        }
        file_put_contents($layout_dir . DIRECTORY_SEPARATOR . "$name.php", $dom);
    }

    public function renderFile($view, $model = false) {
        if ($model == FALSE) {
            $model = $this;
        }
        ob_start();
        ob_implicit_flush(false);
        extract([$model], EXTR_OVERWRITE);
        require($view);

        return ob_get_clean();
    }

}
