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

namespace themey\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use themey\Generator\Theme;
use themey\Helper\Display;

/**
 * Description of GenerateLayoutCommand
 *
 * @author Ramadan Juma
 */
class GenerateLayoutCommand extends Command {

    public function configure() {
        $this->setName("generate:layout")
                ->setHelp("Generate theme layout,theme name and layout name are required.")
                ->setDescription("Generate theme layout")
                ->addOption("theme", 't', InputOption::VALUE_REQUIRED, "Theme Name")
                ->addOption("name", 'l', InputOption::VALUE_REQUIRED, "Layout Name")
                ->addOption("asset","a", InputOption::VALUE_IS_ARRAY|InputOption::VALUE_OPTIONAL,"Asset bundle used by layout",[])
                ->addOption("path", 'p', InputOption::VALUE_OPTIONAL, "Path to layout template");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $theme = $input->getOption("theme");
        $layout = $input->getOption("name");
        $path = $input->getOption("path");
        $assets = $input->getOption("asset");
        $root = \themey\Application::$workingDir;
        Display::setOutput($output);
        if ($theme == FALSE) {
            return $output->writeln("Theme name is required.");
        }

        if ($layout == FALSE) {
            return $output->writeln("Layout name is required.");
        }
        if ($path == FALSE) {
            if (file_exists($root . "/../theme/$layout.html")) {
                $path = $root . "/../theme/index.html";
            } elseif (file_exists($root . "/../themes/$theme/$layout.html")) {
                $path = $root . "/../themes/$theme/$layout.html";
            }
        }

        if (!file_exists($path)) {
            return $output->writeln("Theme layout file $path does not exist, layout will not be generated.");
        }
        $gen = new Theme($theme,false,$output);
        $gen->current_layout = $layout;
        $gen->generateLayout($layout, $path);      
        if(count($assets) == 0){
            $assets[] = $layout;
        }
        $gen->generateAssets($assets);
    }

}
