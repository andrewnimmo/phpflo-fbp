<?php
/*
 * This file is part of the phpflo\phpflo-fbp package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\PhpFlo\Loader;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use PhpFlo\Loader\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamFile
     */
    private $file;

    /**
     * @expectedException \PhpFlo\Exception\LoaderException
     */
    public function testStaticLoadException()
    {
        $data = Loader::load('test.yml');
    }

    public function testLoadYamlFile()
    {
        $yaml = <<<EOF
properties:
    name: ''
initializers: {  }
processes:
    ReadFile:
        component: ReadFile
        metadata: { label: ReadFile }
    SplitbyLines:
        component: SplitStr
        metadata: { label: SplitStr }
    Display:
        component: Output
        metadata: { label: Output }
    CountLines:
        component: Counter
        metadata: { label: Counter }
connections:
    -
        src: { process: ReadFile, port: OUT }
        tgt: { process: SplitbyLines, port: IN }
    -
        src: { process: ReadFile, port: ERROR }
        tgt: { process: Display, port: IN }
    -
        src: { process: SplitbyLines, port: OUT }
        tgt: { process: CountLines, port: IN }
    -
        src: { process: CountLines, port: COUNT }
        tgt: { process: Display, port: IN }

EOF;

        $url = $this->createFile('test.yml', $yaml);
        $definition = Loader::load($url);

        $this->assertArrayHasKey('connections', $definition);
    }

    public function testLoadJsonFile()
    {
        $json = <<< EOF
{
    "properties": {
        "name": ""
    },
    "initializers": [],
    "processes": {
        "ReadFile": {
            "component": "ReadFile",
            "metadata": {
                "label": "ReadFile"
            }
        },
        "SplitbyLines": {
            "component": "SplitStr",
            "metadata": {
                "label": "SplitStr"
            }
        },
        "Display": {
            "component": "Output",
            "metadata": {
                "label": "Output"
            }
        },
        "CountLines": {
            "component": "Counter",
            "metadata": {
                "label": "Counter"
            }
        }
    },
    "connections": [
        {
            "src": {
                "process": "ReadFile",
                "port": "OUT"
            },
            "tgt": {
                "process": "SplitbyLines",
                "port": "IN"
            }
        },
        {
            "src": {
                "process": "ReadFile",
                "port": "ERROR"
            },
            "tgt": {
                "process": "Display",
                "port": "IN"
            }
        },
        {
            "src": {
                "process": "SplitbyLines",
                "port": "OUT"
            },
            "tgt": {
                "process": "CountLines",
                "port": "IN"
            }
        },
        {
            "src": {
                "process": "CountLines",
                "port": "COUNT"
            },
            "tgt": {
                "process": "Display",
                "port": "IN"
            }
        }
    ]
}
EOF;

        $url = $this->createFile('test.json', $json);
        $definition = Loader::load($url);

        $this->assertArrayHasKey('connections', $definition);
    }

    public function testLoadFbpFile()
    {
        $fbp = <<<EOF
ReadFile(ReadFile) OUT -> IN SplitbyLines(SplitStr)
ReadFile(ReadFile) ERROR -> IN Display(Output)
SplitbyLines(SplitStr) OUT -> IN CountLines(Counter)
CountLines(Counter) COUNT -> IN Display(Output)
EOF;

        $url = $this->createFile('test.fbp', $fbp);
        $definition = Loader::load($url);

        $this->assertArrayHasKey('connections', $definition);
    }

    private function createFile($name, $content)
    {
        $root = vfsStream::setup();
        $this->file = vfsStream::newFile($name)->at($root);
        $this->file->setContent($content);

        return $this->file->url();
    }
}
