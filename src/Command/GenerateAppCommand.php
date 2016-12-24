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

/**
 * Description of GenerateAppCommand
 *
 * @author Ramadan Juma
 */
class GenerateAppCommand extends \Symfony\Component\Console\Command\Command {

    public function configure() {
        $this->setName("generate:app")
                ->setDescription("Generate application template");
        $this->addOption("path", "p", InputOption::VALUE_OPTIONAL, "Application path");
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $path = $input->getOption("path");
        if ($path == FALSE) {
            $path = \themey\Application::$workingDir;
        } else {
            if (!file_exists($path)) {
                $output->writeln("Path $path does not exist.");
            }
        }
        $context = realpath($path);
        $dirs = [
            'controllers',
            'themes',
            'models',
            'config',
            'views' => ['layouts'],
            'web' => ['assets' => ['themes']]
        ];
        \themey\Helper\FileHelper::createFolders($dirs, $context);
        $output->writeln("app generated successfully");
    }

}
