<?php
/*
 * This file is part of the phpflo/phpflo-fbp package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\PhpFlo\Fbp;

use PhpFlo\Fbp\FbpDumper;

class FbpDumperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $source = [
        'properties' => ['name' => '',],
        'initializers' => [],
        'processes' => [
            'ReadFile' => [
                'component' => 'ReadFile',
                'metadata' => [
                    'label' => 'ReadFile',
                ],
            ],
            'SplitbyLines' => [
                'component' => 'SplitStr',
                'metadata' => [
                    'label' => 'SplitStr',
                ],
            ],
            'Display' => [
                'component' => 'Output',
                'metadata' => [
                    'label' => 'Output',
                ],
            ],
            'CountLines' => [
                'component' => 'Counter',
                'metadata' => [
                    'label' => 'Counter',
                ],
            ]
        ],
        'connections' => [
            [
                'src' => [
                    'process' => 'ReadFile',
                    'port' => 'OUT',
                ],
                'tgt' => [
                    'process' => 'SplitbyLines',
                    'port' => 'IN',
                ],
            ],
            [
                'src' => [
                    'process' => 'ReadFile',
                    'port' => 'ERROR',
                ],
                'tgt' => [
                    'process' => 'Display',
                    'port' => 'IN',
                ],
            ],
            [
                'src' => [
                    'process' => 'SplitbyLines',
                    'port' => 'OUT',
                ],
                'tgt' => [
                    'process' => 'CountLines',
                    'port' => 'IN',
                ],
            ],
            [
                'src' => [
                    'process' => 'CountLines',
                    'port' => 'COUNT',
                ],
                'tgt' => [
                    'process' => 'Display',
                    'port' => 'IN',
                ],
            ],
        ],
    ];

    public function testJsonDump()
    {
        $expected = <<< EOF
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

        $json = FbpDumper::toJson($this->source);
        $this->assertEquals($expected, $json);
    }

    public function testYamlDump()
    {
        $expected1 = <<<EOF
properties:
    name: ''
initializers: {  }
processes:
    ReadFile:
        component: ReadFile
        metadata:
            label: ReadFile
    SplitbyLines:
        component: SplitStr
        metadata:
            label: SplitStr
    Display:
        component: Output
        metadata:
            label: Output
    CountLines:
        component: Counter
        metadata:
            label: Counter
connections:
    -
        src:
            process: ReadFile
            port: OUT
        tgt:
            process: SplitbyLines
            port: IN
    -
        src:
            process: ReadFile
            port: ERROR
        tgt:
            process: Display
            port: IN
    -
        src:
            process: SplitbyLines
            port: OUT
        tgt:
            process: CountLines
            port: IN
    -
        src:
            process: CountLines
            port: COUNT
        tgt:
            process: Display
            port: IN

EOF;

        $expected2 = <<<EOF
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


        $yaml = FbpDumper::toYaml($this->source, 4);
        $this->assertEquals($expected1, $yaml);
        $yaml = FbpDumper::toYaml($this->source);
        $this->assertEquals($expected2, $yaml);
    }

    public function testFbpDump()
    {
        $this->markTestSkipped();
    }
}