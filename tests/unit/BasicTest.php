<?php

use themey\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class BasicTest extends \Codeception\TestCase\Test {

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before() {
        if (file_exists(__DIR__ . "/../_output/app")) {
            $this->rrmdir(__DIR__ . "/../_output/app");
        }
    }

    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object))
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            rmdir($dir);
        }
    }

    protected function _after() {
        
    }

    public function testConstructor() {
        $application = new Application();
        $this->assertEquals('Yii2 theme generator', $application->getName(), '__construct() takes the application name as its first argument');
        $this->assertEquals(Application::VERSION, $application->getVersion(), '__construct() takes the application version as its second argument');
    }

    public function testSetGetName() {
        $application = new Application();
        $application->setName('foo');
        $this->assertEquals('foo', $application->getName(), '->setName() sets the name of the application');
    }

    public function testSetGetVersion() {
        $application = new Application();
        $this->assertEquals(Application::VERSION, $application->getVersion(), '->setVersion() sets the version of the application');
    }

    public function testGenerateApp() {
        Application::$workingDir = __DIR__ . "/../_output/app";
        $app = new Application;
        $app->setCatchExceptions(false);
        $app->setAutoExit(false);
        $tester = new ApplicationTester($app);
        ob_start();
        $tester->run(array("command" => 'generate:app'));
        ob_end_clean();
        $this->assertDirectoryExists(__DIR__ . "/../_output/app/controllers", "generate:app generate application structure");
    }

    public function testGenerateTheme() {
        Application::$workingDir = __DIR__ . "/../_output/app";
        $app = new Application;
        $app->setCatchExceptions(false);
        $app->setAutoExit(false);
        $tester = new ApplicationTester($app);
        ob_start();
        $tester->run(array("command" => "generate:theme", "--name" => "test"));
        ob_end_clean();
        $this->assertDirectoryExists(__DIR__ . "/../_output/app/themes/test", "generate:theme generate theme template");
    }

}
