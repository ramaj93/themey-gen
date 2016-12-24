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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use PHPHtmlParser\Dom;

/**
 * Description of GeneratorCommand
 *
 * @author Ramadan Juma
 */
class GenerateThemeCommand extends \Symfony\Component\Console\Command\Command {

    protected function configure() {
        $this->setName("generate:theme")
                ->setHelp("Generate theme template")
                ->setDescription("Generate theme template");
        $this->addOption("path", 'p', InputOption::VALUE_OPTIONAL, "Path to default theme layout template");
        $this->addOption("name", 't', InputOption::VALUE_OPTIONAL, "Theme Name");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $path = $input->getOption("path");
        $name = $input->getOption("name");
        $root = WORKING_DIR;
        if ($path == FALSE) {
            if (file_exists($root . "/../theme/index.html")) {
                $path = $root . "/../theme/index.html";
            }
        }
        if (!file_exists($path)) {
            $output->writeln("Theme layout file $path does not exist, layout will not be generated.");
            $path = FALSE;
        }
        if ($name == FALSE) {
            $output->writeln("Theme name not specified default 'basic' name will be used.");
            $name = "basic";
        }
        $theme = new \themey\Generator\Theme($name);
        $theme->generateStructure();
        $theme->generateAssets();
        if ($path != FALSE) {
            $theme->generateLayout("main", $path);
        }
    }

}
