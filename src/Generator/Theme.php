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

    public function __construct($name, $layout = false, $callback = false) {
        $this->name = $name;
        $this->layout_path = $layout;
    }

    public function generateStructure() {
        $dirs = [
            'components',
            'widgets',
            'assets',
            'gii'
        ];
        $theme_path = WORKING_DIR . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . $this->name;
        FileHelper::createFolders($dirs, $theme_path);
    }

    public function generateAssets($layouts = []) {
        $asset_path = WORKING_DIR . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . "assets";
        $tpl_path = __DIR__ . "/../Templates/Asset.php";
        foreach ($layouts as $layout) {
            $layout_name = ucfirst($layout) . "Asset.php";
            $this->current_layout = $layout;
            $contents = $this->renderFile($tpl_path);
            file_put_contents($asset_path . DIRECTORY_SEPARATOR . $layout_name, $contents);
        }
    }

    public function generateLayout($name, $layout_path) {
        $dom = new Dom;
        $dom->setOptions([
            'removeScripts' => FALSE,
            'preserveLineBreaks' => TRUE
        ]);
        $root = WORKING_DIR;
        $dom->loadFromFile($layout_path);
        $theme_base = dirname($layout_path);
        $scripts = $dom->getElementsByTag("script");
        $links = $dom->getElementsByTag("link");
        $images = $dom->getElementsByTag("img");

        $asset_css = [];
        $assets_path = $root . DIRECTORY_SEPARATOR . "web/themes" . DIRECTORY_SEPARATOR . $name;
        foreach ($links as $link) {
            $href = $link->getAttribute("href");
            if (strpos($href, "http") === FALSE) {
                $this->assets[$name]['css'][] = $href;
                $src_path = $theme_base . DIRECTORY_SEPARATOR . $href;
                $base_name = basename(dirname($src_path));
                $asset_css[] = $base_name . "/" . basename($src_path);
                \themey\Helper\FileHelper::copyFile($src_path, $assets_path . DIRECTORY_SEPARATOR . $base_name);
                $link->delete();
            }
        }
        $asset_js = [];
        foreach ($scripts as $script) {
            $href = $script->getAttribute("src");
            if (strpos($href, "http") === FALSE) {
                $this->assets[$name]['js'][] = $href;
                $src_path = $theme_base . DIRECTORY_SEPARATOR . $href;
                $base_name = basename(dirname($src_path));
                $asset_js[] = $base_name . "/" . basename($src_path);
                \themey\Helper\FileHelper::copyFile($src_path, $assets_path . DIRECTORY_SEPARATOR . $base_name);
                $script->delete();
            }
        }

        foreach ($images as $img) {
            $href = $img->getAttribute("src");
            if (strpos($href, "http") === FALSE) {
                $src_path = $theme_base . DIRECTORY_SEPARATOR . $href;
                $base_name = basename(dirname($src_path));
                \themey\Helper\FileHelper::copyFile($src_path, $assets_path . DIRECTORY_SEPARATOR . $base_name);
            }
        }
        $bundle_name = ucfirst($name) . "Asset";
        $rep = <<<EOL
<?php\n\n
/* @var \$this \yii\web\View */
use app\\themes\\$this->name\\assets\\$bundle_name;\n\n
\$bundle = $bundle_name::register(\$this);\n\n
                
\$this->beginPage()?>\n
<html
EOL;
        $dom = str_replace("<html", $rep, $dom);
        $dom = str_replace("</head>", "<?=\$this->head();?>\n</head>", $dom);
        $dom = str_replace("</body>", "<?=\$this->endBody();?>\n</body>", $dom);
        $dom = str_replace("</html>", "</html>\n<?=\$this->endPage();?>", $dom);
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
