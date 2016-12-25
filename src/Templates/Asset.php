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

/* @var $this themey\Generator\Theme */
?>
<?="<?php\n"?>

namespace app\themes\<?=$this->name?>\assets;

use yii\web\AssetBundle;

class <?= ucfirst($this->current_layout)?>Asset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    <?php if(array_key_exists($this->current_layout,$this->assets))
        foreach ($this->assets[$this->current_layout]['css'] as $css):?>
    '<?=$css?>',
    <?php endforeach;?>
    ];
    
    public $js = [
        <?php if(array_key_exists($this->current_layout,$this->assets))
            foreach ($this->assets[$this->current_layout]['js'] as $js):?>
        '<?=$js?>',
        <?php endforeach;?>
    ];
    
}